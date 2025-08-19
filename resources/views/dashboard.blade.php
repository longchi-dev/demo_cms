<x-app-layout>
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
                    <div class="user-info">
                    </div>

                    <button id="logoutButton" class="mt-4 bg-red-500 text-white p-2 rounded hover:bg-red-600">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            
            if (!token) {
                window.location.href = '/login';
                return;
            }

            fetch('/api/user', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Unauthorized or Invalid Token');
                }
                return response.json();
            })
            .then(data => {
                const userData = data;
                console.log('User data:', userData);

                const userInfoDiv = document.querySelector('.user-info');
                
                const userHtml = `
                    <div>
                        <h3>User Information from API:</h3>
                        <p>Name: ${userData.name}</p>
                        <p>Email: ${userData.email}</p>
                    </div>
                `;
                
                userInfoDiv.innerHTML = userHtml;
            })
            .catch(error => {
                console.error('Error:', error);
            });

            const logoutButton = document.getElementById('logoutButton');
            logoutButton.addEventListener('click', function() {
                fetch('/api/logout', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Logout failed');
                    }
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                });
            });
        });
    </script>
</x-app-layout>
