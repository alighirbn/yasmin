<x-app-layout>

    <x-slot name="header">
        <!-- App CSS -->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('contract.nav.navigation')
        @include('service.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons mb-4">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success mb-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="container">
                        <h1>التقاط الصور والاوليات</h1>

                        <div id="my_camera" style="width:640px; height:480px;"></div>

                        <button id="capture" class="btn btn-primary">التقاط</button>
                        <button id="upload" class="btn btn-success" style="display:none;">حفظ</button>

                        <form id="uploadForm" action="{{ route('contract.archivestore', $contract->url_address) }}"
                            method="POST" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            <div id="imageInputsContainer"></div> <!-- Container for image inputs -->
                            <button type="submit" class="btn btn-success" style="display: none;">Upload</button>
                        </form>

                        <div id="preview" class="mt-3"></div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/webcamjs@1.0.25/webcam.min.js"></script>
                    <script>
                        // Configure WebcamJS
                        Webcam.set({
                            width: 640,
                            height: 480,
                            image_format: 'jpeg',
                            jpeg_quality: 90
                        });

                        // Attach the camera to the div
                        Webcam.attach('#my_camera');

                        // Capture the image
                        document.getElementById('capture').addEventListener('click', function() {
                            Webcam.snap(function(data_uri) {
                                // Display the captured image
                                document.getElementById('preview').innerHTML += '<img src="' + data_uri +
                                    '" width="320" alt="Captured Image" class="mb-2">';

                                // Create a hidden input for each captured image
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'images[]'; // This allows the server to treat it as an array
                                input.value = data_uri; // Store the captured image in the input
                                document.getElementById('imageInputsContainer').appendChild(input);

                                // Show upload button if at least one image is captured
                                document.getElementById('upload').style.display = 'block';
                            });
                        });

                        // Handle upload of all images
                        document.getElementById('upload').addEventListener('click', function() {
                            if (document.querySelectorAll('#imageInputsContainer input').length > 0) {
                                document.getElementById('uploadForm').submit(); // Submit the form directly
                            } else {
                                alert('Please capture at least one image before uploading.');
                            }
                        });
                    </script>

                </div>
            </div>
        </div>

</x-app-layout>
