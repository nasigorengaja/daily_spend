<html>

<head>
    <title>Create Spending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-200">
    <form method="POST" action="{{ route('create.spending') }}">
        @csrf
        <div class="form-group mb-4">
            <label for="input_type">Nama Kegiatan</label>
            <input type="text" name="name" class="form-control" id="input_type" aria-describedby="input_type"
                placeholder="Masukkan nama kegiatan" required>
        </div>
        <div class="form-group mb-4">
            <label for="input_type">Nominal</label>
            <input type="text" name="amount" class="form-control" id="input_type" aria-describedby="input_type"
                placeholder="Masukkan nominal spending" required>
        </div>
        <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Submit</button>
    </form>
</body>

</html>
