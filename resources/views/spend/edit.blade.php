<html>

<head>
    <title>Create Spending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body> 
    <form method="POST" action="{{ route('post.edit.spend') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $spend->id }}">
        <div class="form-group mb-4">
            <label for="input_type">Nama Kegiatan</label>
            <input type="text" name="name" value="{{ $spend->name }}" placeholder="Masukkan nama kegiatan" required>
        </div>
        <div class="form-group mb-4">
            <label for="input_type">Nominal</label>
            <input type="text" name="amount" value="{{ $spend->amount }}" placeholder="Masukkan nominal spending" required>
        </div>
        <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Edit</button>
    </form>
</body>

</html>
