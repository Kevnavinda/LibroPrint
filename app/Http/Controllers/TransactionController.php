<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class TransactionController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('transaction.index', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:225',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id'
        ]);

        try {
            DB::beginTransaction();

            $dateCode = now()->format('Ymd');
            $lastTransaction = Transaction::whereDate('created_at', today())->latest()->first();

            if ($lastTransaction) {
                $lastNumber = intval(substr($lastTransaction->receipt_no, -3));
                $sequence = $lastNumber + 1;
            } else {
                $sequence = 1;
            }

            $receiptNo = 'TRX-' . $dateCode . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'receipt_no' => $receiptNo,
                'borrower_name' => $request->borrower_name,
                'borrow_date' => now(),
            ]);

            foreach ($request->book_ids as $bookId) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $bookId,
                ]);
            }

            DB::commit();


            try {
                $connector = new WindowsPrintConnector("struk");
                $printer = new Printer($connector);

                $printer->initialize();

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("GO-NA\n");
                $printer->text("Form Kevin\n");
                $printer->text("--------------------------------\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("No   : " . $transaction->receipt_no . "\n");
                $printer->text("Tgl  : " . $transaction->borrow_date->format('d-m-Y H:i') . "\n");
                $printer->text("Nama : " . $transaction->borrower_name . "\n");
                $printer->text("--------------------------------\n");

                $printer->text("Buku Dipinjam:\n");
                foreach ($transaction->details as $detail) {
                    $printer->text("- " . $detail->book->title . "\n");
                }

                $printer->text("--------------------------------\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("Harap kembalikan tepat waktu.\n");
                $printer->text("Terima Kasih!\n");

                $printer->feed(3);
                $printer->cut();
                $printer->close();
            } catch (\Exception $e) {
                return redirect()->route('transaction.index')
                    ->with('warning', 'Transaksi Berhasil, tapi Gagal Print: ' . $e->getMessage());
            }
            return redirect()->route('transaction.index')
                ->with('success', 'Transaksi Berhasil & Struk Dicetak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal Menyimpan: ' . $e->getMessage()]);
        }
    }
}
