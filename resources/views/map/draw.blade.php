<x-app-layout>

    <x-slot name="header">
        <style>
            .image-container {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-image {
                width: 100%;
                height: auto;
            }

            .overlay-div {
                position: absolute;
                width: 0.85%;
                height: 1.4%;
            }

            a.fill-div {
                display: block;
                height: 100%;
                width: 100%;
                text-decoration: none;
                border-radius: 100%;
                text-align: center;
                line-height: 1%;
                background-color: rgb(0, 195, 255);
                mix-blend-mode: multiply;
            }

            .overlay-div:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                top: -35px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 3px;
                border-radius: 5px;
                white-space: nowrap;
                z-index: 10;
            }

            /* Canvas specific styles */
            #drawingCanvas {
                position: absolute;
                top: 0;
                left: 0;
                z-index: 5;
            }

            /* Control Panel Styling */
            .controls {
                margin-bottom: 10px;
            }

            .controls label {
                margin-right: 10px;
            }

            .controls input,
            .controls select {
                margin-right: 20px;
            }
        </style>
        <div class="flex justify-start">
            @include('map.nav.navigation')
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Control Panel for Drawing -->
                    <div class="controls" style="text-align: right;">
                        <label for="colorPicker">لون الخط:</label>
                        <input type="color" id="colorPicker" value="#ff0000">

                        <label for="lineThickness">سُمك الخط:</label>
                        <input type="range" id="lineThickness" min="1" max="10" value="2">

                        <label for="lineType">نوع الخط:</label>
                        <select id="lineType">
                            <option value="solid">مستقيم</option>
                            <option value="dashed">متقطع</option>
                            <option value="dotted">منقط</option>
                        </select>

                        <button onclick="clearCanvas()">مسح الرسم</button>
                    </div>

                    <div class="image-container">
                        <!-- Map Image -->
                        <img src="{{ asset('images/background.jpg') }}" alt="Image" class="main-image"
                            id="mapImage">

                        <!-- Canvas for Drawing -->
                        <canvas id="drawingCanvas"></canvas>

                        <!-- Building Overlays -->
                        @foreach ($buildings as $building)
                            <div class="overlay-div"
                                style="top: {{ $building->building_map_y }}%; left: {{ $building->building_map_x }}%;"
                                data-tooltip=" {{ $building->building_number }}">
                                <a href="{{ route('building.edit', $building->url_address) }}" class="fill-div"></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Drawing -->
    <script>
        // Setup canvas to match the map image dimensions
        const canvas = document.getElementById('drawingCanvas');
        const ctx = canvas.getContext('2d');
        const mapImage = document.getElementById('mapImage');

        // Controls
        const colorPicker = document.getElementById('colorPicker');
        const lineThickness = document.getElementById('lineThickness');
        const lineType = document.getElementById('lineType');

        // Resize the canvas to match the image
        function resizeCanvas() {
            canvas.width = mapImage.clientWidth;
            canvas.height = mapImage.clientHeight;
        }

        window.onload = resizeCanvas;
        window.onresize = resizeCanvas;

        // Variables for drawing
        let drawing = false;
        let prevX = 0;
        let prevY = 0;

        // Start drawing
        canvas.addEventListener('mousedown', function(e) {
            drawing = true;
            prevX = e.offsetX;
            prevY = e.offsetY;
        });

        // Draw on the canvas
        canvas.addEventListener('mousemove', function(e) {
            if (drawing) {
                const currentX = e.offsetX;
                const currentY = e.offsetY;

                // Set color and line thickness
                ctx.strokeStyle = colorPicker.value;
                ctx.lineWidth = lineThickness.value;

                // Set line style
                if (lineType.value === 'dashed') {
                    ctx.setLineDash([5, 5]);
                } else if (lineType.value === 'dotted') {
                    ctx.setLineDash([1, 5]);
                } else {
                    ctx.setLineDash([]);
                }

                ctx.beginPath();
                ctx.moveTo(prevX, prevY);
                ctx.lineTo(currentX, currentY);
                ctx.stroke();
                ctx.closePath();

                prevX = currentX;
                prevY = currentY;
            }
        });

        // Stop drawing
        canvas.addEventListener('mouseup', function() {
            drawing = false;
        });

        // Prevent default drag behavior
        canvas.addEventListener('mouseleave', function() {
            drawing = false;
        });

        // Clear canvas
        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    </script>

</x-app-layout>
