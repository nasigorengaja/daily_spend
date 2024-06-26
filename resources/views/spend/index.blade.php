<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Spend</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-green-200">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="mb-4 ml-2">
            <?php
            // Set timezone if needed
            date_default_timezone_set('Asia/Jakarta');
            
            // Get current date
            $day = date('j'); // Day without leading zero
            $month = date('F'); // Full month name
            $year = date('Y'); // Full year
            echo "Hari ini $day $month $year";
            ?> <br><br>
            <a href="{{ route('create') }}" class="justify-end bg-green-500 text-white px-4 py-2 rounded">+ Tambah
                Pengeluaran</a>
        </div>
        <div class="mb-4 flex space-x-2">
            <input id="dateRangePicker" class="block w-full p-2 border border-gray-300 rounded-md"
                placeholder="Select date range" />
            <button id="applyButton" class="bg-green-500 text-white px-4 py-2 rounded">Apply</button>
            <button id="deleteButton" class="bg-red-500 text-white px-4 py-2 rounded hidden">Delete</button>
        </div>
        <div class="mb-4 ml-2">
            {{-- <label for="actionSelect" class="block text-sm font-medium text-gray-700">Choose Action:</label>
            <select id="actionSelect" class="bg-white border rounded p-2 mb-4">
                <option value="export">Export</option>
                <option value="import">Import</option>
            </select>

            <div id="exportContainer">
                <button id="exportButton" class="bg-blue-500 text-white px-4 py-2 rounded">Export Spends</button>
            </div> --}}

            {{-- <div id="importContainer" class="hidden">
                <form action="{{ route('spends.import') }}" method="POST" enctype="multipart/form-data"
                    class="inline-block">
                    @csrf
                    <input type="file" name="file" class="bg-white border rounded p-2">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Import Spends</button>
                </form>
            </div> --}}
        </div>
        <table id="spendTable" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Kegiatan</th>
                    <th scope="col" class="px-6 py-3">Nominal</th>
                    <th scope="col" class="px-6 py-3">Tgl kegiatan</th>
                    <th scope="col" class="px-6 py-3">Tgl edit</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($spend as $s)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $s->name }}
                        </th>
                        <td class="px-6 py-4">Rp. {{ $s->amount }}</td>
                        <td class="px-6 py-4">{{ optional($s->created_at)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ optional($s->updated_at)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('get.edit.spend', $s->id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- untuk paginate --}}
        {{-- <div class="mt-4">
            {{ $spend->links() }}
        </div> --}}
        <div id="totalAmount">Total : Rp. {{ $total }}</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const actionSelect = document.getElementById('actionSelect');
            // const exportContainer = document.getElementById('exportContainer');
            // const importContainer = document.getElementById('importContainer');
            // const exportButton = document.getElementById('exportButton');
            const deleteButton = document.getElementById('deleteButton');

            let currentDateRange = [];

            // actionSelect.addEventListener('change', function() {
            //     if (actionSelect.value === 'export') {
            //         importContainer.classList.add('hidden');
            //         exportContainer.classList.remove('hidden');
            //     } else {
            //         exportContainer.classList.add('hidden');
            //         importContainer.classList.remove('hidden');
            //     }
            // });

            flatpickr("#dateRangePicker", {
                mode: "range",
                dateFormat: "Y-m-d"
            });

            document.getElementById('applyButton').addEventListener('click', function() {
                const dateRange = document.getElementById('dateRangePicker')._flatpickr.selectedDates;
                if (dateRange.length === 2) {
                    const startDate = dateRange[0].toISOString().split('T')[0];
                    const endDate = dateRange[1].toISOString().split('T')[0];
                    currentDateRange = [startDate, endDate];

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`{{ route('get.spend.data') }}?start=${startDate}&end=${endDate}`, {
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const tableBody = document.querySelector('#spendTable tbody');
                            tableBody.innerHTML = '';

                            function formatDate(dateString) {
                                const date = new Date(dateString);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            }

                            let totalAmount = 0;

                            data.forEach((spend, index) => {
                                totalAmount += spend.amount;

                                const row = document.createElement('tr');
                                row.className = index % 2 === 0 ?
                                    'bg-white dark:bg-gray-900 border-b dark:border-gray-700' :
                                    'bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700';
                                row.innerHTML = `
                                <td class="px-6 py-4">${index+1}</td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${spend.name}</th>
                                <td class="px-6 py-4">Rp. ${spend.amount}</td>
                                <td class="px-6 py-4">${formatDate(spend.created_at)}</td>
                                <td class="px-6 py-4">${formatDate(spend.updated_at)}</td>
                                <td class="px-6 py-4">
                                    <a href="/edit/${spend.id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            `;
                                tableBody.appendChild(row);
                            });

                            document.getElementById('totalAmount').textContent =
                                `Total: Rp. ${totalAmount}`;
                            deleteButton.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        });
                } else {
                    console.log('Please select a date range.');
                }
            });

            deleteButton.addEventListener('click', function() {
                if (currentDateRange.length === 2) {
                    if (confirm(
                            'Apakah Anda yakin ingin menghapus pengeluaran untuk rentang tanggal ini?')) {
                        const [startDate, endDate] = currentDateRange;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content');

                        fetch(`{{ route('delete.spend.data') }}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    start: startDate,
                                    end: endDate
                                }),
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Refresh the table after deletion
                                document.getElementById('applyButton').click();
                                alert(data.message);
                            })
                            .catch(error => {
                                console.error('There was a problem with the delete operation:', error);
                            });
                    }
                } else {
                    alert('Please select a date range to delete.');
                }
            });
        });
    </script>

</body>

</html>
