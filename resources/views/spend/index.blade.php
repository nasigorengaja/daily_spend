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
        <div class="mb-4">
            Pengeluaran Harian Afvrita de Hadiwijaya <br><br>
            <a href="{{ route('create') }}" class="justify-end bg-green-500 text-white px-4 py-2 rounded">+ Tambah
                Pengeluaran</a>
        </div>
        <div class="mb-4 flex space-x-2">
            <input id="dateRangePicker" class="block w-full p-2 border border-gray-300 rounded-md"
                placeholder="Select date range" />
            <button id="applyButton" class="bg-green-500 text-white px-4 py-2 rounded">Apply</button>
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
                        <td class="px-6 py-4">{{ $s->id }}</td>
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $s->name }}</th>
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
        {{-- untuk paginate
        <div class="mt-4">
            {{ $spend->links() }}
        </div> --}}
    </div>
    {{-- <div>Total Keseluruhan : Rp. {{ $total_all }}</div> --}}
    <div id="totalAmount">Total : Rp. {{ $total }}</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#dateRangePicker", {
                mode: "range",
                dateFormat: "d/m/Y"
            });

            document.getElementById('applyButton').addEventListener('click', function() {
                const dateRange = document.getElementById('dateRangePicker')._flatpickr.selectedDates;
                if (dateRange.length === 2) {
                    const startDate = dateRange[0].toISOString().split('T')[0];
                    const endDate = dateRange[1].toISOString().split('T')[0];

                    // Adding CSRF token
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
                            // Clear existing table rows
                            const tableBody = document.querySelector('#spendTable tbody');
                            tableBody.innerHTML = '';

                            // Helper function to format date
                            function formatDate(dateString) {
                                const date = new Date(dateString);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            }

                            // Calculate the total amount
                            let totalAmount = 0;

                            // Populate table with new data
                            data.forEach((spend, index) => {
                                totalAmount += spend.amount;

                                const row = document.createElement('tr');
                                row.className = index % 2 === 0 ?
                                    'bg-white dark:bg-gray-900 border-b dark:border-gray-700' :
                                    'bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-700';
                                row.innerHTML = `
                        <td class="px-6 py-4">${spend.id}</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${spend.name}</th>
                        <td class="px-6 py-4">Rp. ${spend.amount}</td>
                        <td class="px-6 py-4">${formatDate(spend.created_at)}</td>
                        <td class="px-6 py-4">${formatDate(spend.updated_at)}</td>
                        <td class="px-6 py-4">
                            <a href="/edit/spend/${spend.id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        </td>
                    `;
                                tableBody.appendChild(row);
                            });

                            // Update the total amount in the UI
                            document.getElementById('totalAmount').textContent = `Total: Rp. ${totalAmount}`;
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        });
                } else {
                    console.log('Please select a date range.');
                }
            });
        });
    </script>
</body>

</html>
