@section('title', __('Exam Mark'))

<x-layouts.app>
    @livewire('exam-mark-table')

    @push('scripts')
    <script>
        function attachMarkListeners() {
            document.querySelectorAll('.update-mark').forEach(function(input) {
                input.removeEventListener('change', handleMarkChange);
                input.addEventListener('change', handleMarkChange);
            });
        }

        function handleMarkChange(event) {
            const input = event.target;
            const value = input.value;
            const url = input.dataset.url;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to update the mark to ${value}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: JSON.stringify({
                                marks: value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Mark updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                Livewire.dispatch('refreshExamMarkTable');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to update mark.'
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        });
                } else {
                    // If cancelled, reset input value to previous
                    input.value = input.defaultValue;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            attachMarkListeners();

            Livewire.hook('message.processed', (message, component) => {
                attachMarkListeners();
            });
        });
    </script>
    @endpush
</x-layouts.app>