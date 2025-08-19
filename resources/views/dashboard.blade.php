@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <button id="export-button" type="button" class="bg-grey-500 text-white px-4 py-2 rounded-lg">
            Export Data
        </button>
        <p id="export-status" class="mt-2 text-sm text-gray-600 dark:text-gray-300"></p>
    </div>
@endsection

@push('js')
    <script>
        $('#export-button').on('click', function() {
            console.log('aababba')
            $.ajax({
                url: '/export/data',
                method: 'POST',
                data: {
                        
                },
                success: function(response) {
                    console.log('Export bắt đầu:', response);
                    $('#export-status').text('Đang xuất dữ liệu...');

                    let pollingInterval = setInterval(() => {
                        $.ajax({
                            url: "/export/status",
                            method: 'GET',
                            success: function (statusResponse) {
                                console.log('Status:', statusResponse);
                                $('#export-status').text(statusResponse.message);

                                if (statusResponse.data && statusResponse.data[0]?.status === 'success') {
                                    clearInterval(pollingInterval);
                                    $('#export-status').text('Xuất dữ liệu hoàn tất!');

                                    const filePath = statusResponse.data[0].path;
                                    window.location.href = filePath;
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Lỗi khi lấy status:', error);
                                $('#export-status').text('Lỗi khi lấy trạng thái.');
                                clearInterval(pollingInterval);
                            }
                        });
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#export-status').text('Xuất dữ liệu thất bại!');
                }
            });
        });
    </script>
@endpush

    

