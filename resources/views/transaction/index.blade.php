<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir LibroPrint</title>
    @vite('resources/css/app.css')
</head>

<body>
    <div class="bg-white min-h-screen flex flex-col justify-center ">

            <div class="flex flex-wrap items-center justify-center">

                <div class="w-full md:w-1/2 mt-8 md:mt-0">
                    <form action="{{ route('transaction.store') }}" method="POST"
                        class="bg-white shadow-lg px-8 pt-6 pb-8 mb-4 border-2">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm"
                                role="alert">
                                <p class="font-bold">Perhatian!</p>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-black text-sm font-bold mb-2"
                                for="borrower_name">
                                Nama Peminjam
                            </label>
                            <input type="text" name="borrower_name" id="borrower_name" required
                                placeholder="Masukkan nama "
                                class="shadow appearance-none border border-green-500 dark:border-gray-700 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                        </div>

                        <div class="mb-6">
                            <label class="block text-black  text-sm font-bold mb-2"
                                for="book_ids">
                                Pilih Buku
                            </label>
                            <div class="relative">
                                <select name="book_ids[]" id="book_ids"
                                    class="shadow appearance-none border border-green-500 dark:border-gray-700 rounded w-full py-3 px-4 text-gray  leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white ">
                                    <option value="">-- Silakan Pilih Buku --</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}">{{ $book->title }} (Stok:
                                            {{ $book->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="w-full bg-gray text-black font-sm py-3 px-4  border-2 rounded focus:outline-none focus:shadow-outline  flex justify-center items-center gap-2 shadow-lg">
                                SIMPAN & PRINT
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
