<html>

<head>
    <title>Daily Spend</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="mb-4">
            Pengeluaran Harian Afvritong Hadiwijaya <br><br>
            <a href="{{ route('create') }}" class="justify-end bg-green-500 text-white px-4 py-2 rounded">+ Tambah Pengeluaran</a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Kegiatan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nominal
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tgl kegiatan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tgl edit
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($spend as $s)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-6 py-4">
                        {{ $s->id }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $s->name }}
                    </th>
                    <td class="px-6 py-4">
                        Rp. {{ $s->amount }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $s->created_at }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $s->updated_at }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{route('get.edit.spend', $s->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>Total : Rp. {{ $total }}</div>
</body>

</html>
