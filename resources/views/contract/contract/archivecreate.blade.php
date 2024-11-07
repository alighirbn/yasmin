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

                    <div class="container mx-auto text-center flex">
                        <div class="camera-container"
                            style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                            <h1 class="mb-4 text-xl font-bold">التقاط الصور والاوليات</h1>
                            <div id="my_camera" class="mx-auto mb-4" style="width:1280px; height:960px;"></div>
                            <button id="capture" class="btn btn-primary mb-2">التقاط</button>
                            <button id="upload" class="btn btn-success" style="display:none;">حفظ</button>
                        </div>

                        <div class="preview-container" style="flex: 1; padding-left: 20px;">
                            <div id="preview" class="mt-3 flex flex-wrap justify-center"></div>
                        </div>
                    </div>

                    <form id="uploadForm" action="{{ route('contract.archivestore', $contract->url_address) }}"
                        method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <div id="imageInputsContainer"></div>
                        <button type="submit" class="btn btn-success" style="display: none;">Upload</button>
                    </form>

                    <div id="imageModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <img id="modalImage" src="" alt="Preview" />
                        </div>
                    </div>

                    <style>
                        .image-frame {
                            border: 2px solid #ccc;
                            border-radius: 8px;
                            padding: 4px;
                            margin: 10px;
                            display: inline-block;
                            position: relative;
                            width: 320px;
                            height: 240px;
                            overflow: hidden;
                        }

                        .image-frame img {
                            max-width: none;
                            transition: transform 0.2s;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        }

                        .modal {
                            display: none;
                            position: fixed;
                            z-index: 1000;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: 100%;
                            overflow: auto;
                            background-color: rgba(0, 0, 0, 0.8);
                        }

                        .modal-content {
                            margin: 15% auto;
                            padding: 20px;
                            border: 1px solid #888;
                            width: 80%;
                        }

                        .modal-content img {
                            width: 100%;
                            height: auto;
                        }

                        .close {
                            color: white;
                            float: right;
                            font-size: 28px;
                            font-weight: bold;
                        }

                        .close:hover,
                        .close:focus {
                            color: #bbb;
                            text-decoration: none;
                            cursor: pointer;
                        }
                    </style>

                    <script src="https://cdn.jsdelivr.net/npm/webcamjs@1.0.25/webcam.min.js"></script>
                    <script>
                        // Configure WebcamJS
                        Webcam.set({
                            width: 1280,
                            height: 960,
                            image_format: 'jpeg',
                            jpeg_quality: 90
                        });

                        Webcam.attach('#my_camera');

                        document.getElementById('capture').addEventListener('click', function() {
                            Webcam.snap(function(data_uri) {
                                const imageWrapper = document.createElement('div');
                                imageWrapper.className = 'image-frame';

                                const imgElement = document.createElement('img');
                                imgElement.src = data_uri;
                                imgElement.width = 320;
                                imgElement.alt = 'Captured Image';
                                imgElement.className = 'block';
                                imgElement.onclick = function() {
                                    openModal(data_uri);
                                };

                                const deleteButton = document.createElement('button');
                                deleteButton.innerText = 'X';
                                deleteButton.className = 'absolute top-0 right-0 bg-red-500 p-1 rounded';
                                deleteButton.onclick = function() {
                                    imageWrapper.remove();
                                    input.remove();

                                    if (document.querySelectorAll('#imageInputsContainer input').length === 0) {
                                        document.getElementById('upload').style.display = 'none';
                                    }
                                };

                                const rotateButton = document.createElement('button');
                                rotateButton.innerText = 'Rotate';
                                rotateButton.className = 'absolute top-0 left-0 bg-blue-500 p-1 rounded';
                                let rotationDegree = 0;
                                rotateButton.onclick = function() {
                                    rotationDegree = (rotationDegree + 90) % 360;
                                    imgElement.style.transform = `translate(-50%, -50%) rotate(${rotationDegree}deg)`;

                                    if (rotationDegree % 180 === 0) {
                                        imageWrapper.style.width = '320px';
                                        imageWrapper.style.height = '240px';
                                    } else {
                                        imageWrapper.style.width = '240px';
                                        imageWrapper.style.height = '320px';
                                    }
                                };

                                imageWrapper.appendChild(imgElement);
                                imageWrapper.appendChild(deleteButton);
                                imageWrapper.appendChild(rotateButton);
                                document.getElementById('preview').appendChild(imageWrapper);

                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'images[]';
                                input.value = data_uri;
                                document.getElementById('imageInputsContainer').appendChild(input);

                                document.getElementById('upload').style.display = 'block';
                            });
                        });

                        function openModal(imageSrc) {
                            document.getElementById('modalImage').src = imageSrc;
                            document.getElementById('imageModal').style.display = 'block';
                        }

                        document.getElementsByClassName('close')[0].onclick = function() {
                            document.getElementById('imageModal').style.display = 'none';
                        }

                        window.onclick = function(event) {
                            if (event.target == document.getElementById('imageModal')) {
                                document.getElementById('imageModal').style.display = 'none';
                            }
                        }

                        document.getElementById('upload').addEventListener('click', function() {
                            if (document.querySelectorAll('#imageInputsContainer input').length > 0) {
                                document.getElementById('uploadForm').submit();
                            } else {
                                alert('Please capture at least one image before uploading.');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
