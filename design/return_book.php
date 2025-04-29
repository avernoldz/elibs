<!-- Revised Code -->
<!-- QR Code Scanner Section -->
<div class="content-wrapper-scroll">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Book Return</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Section for Manual Input -->
                            <div class="col-sm-6 col-12">
                                <form id="form">
                                    <div class="mb-3">
                                        <label class="form-label">Student's Name</label>
                                        <input type="text" class="form-control" name="name" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">LRN</label>
                                        <input type="text" class="form-control" name="lrn" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Book ISBN</label>
                                        <input type="text" id="book-id" class="form-control" name="isbn" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Borrowed Date</label>
                                        <input type="text" class="form-control" id="request_date" name="request-date" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Return Date</label>
                                        <input type="date" class="form-control" id="return-date">
                                    </div>
                                </form>

                                <!-- <div class="book-details mt-3">
                                    <h5>Book Details:</h5>
                                    <p id="book-title">Title: <span>-</span></p>
                                    <p id="book-author">Author: <span>-</span></p>
                                </div> -->
                            </div>


                            <!-- Scanner Note -->
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Scan QR Code</label>
                                    <p class="text-muted">Scan the code using the 2D scanner.</p>
                                </div>
                                <video id="video" width="350" height="250" style="border: 1px solid #c3cbd6;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                            </div>
                        </div>

                        <!-- Buttons and Submission -->
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success btn-lg" id="submit-return" data-bs-toggle="modal" data-bs-target="#confirmation-modal">Submit Return</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmation-modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Book Return</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to return the following book?</p>
                        <ul>
                            <li><strong>Book ISBN:</strong> <span id="confirm-book-isbn" class="text-capitalize"></span></li>
                            <li><strong>Title:</strong> <span id="confirm-book-title" class="text-capitalize"></span></li>
                            <li><strong>Author:</strong> <span id="confirm-book-author" class="text-capitalize"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="confirm-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirm-submit">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const today = new Date();
                const formattedDate = today.toISOString().split('T')[0];

                let scannedData = "";


                document.getElementById('return-date').value = formattedDate;


                const videoElement = document.getElementById('video');
                const canvasElement = document.getElementById('canvas');
                const canvasContext = canvasElement.getContext('2d');
                // const resultElement = document.getElementById('result');

                let canScan = true; // To allow scanning only after the delay

                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    })
                    .then(stream => {
                        videoElement.srcObject = stream;
                        videoElement.setAttribute('playsinline', true); // required to play in iOS Safari
                        videoElement.play();
                        requestAnimationFrame(scanQRCode);
                    })
                    .catch(err => {
                        console.error("Error accessing camera:", err);
                    });

                function scanQRCode() {
                    if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA && canScan) {
                        canvasElement.height = videoElement.videoHeight;
                        canvasElement.width = videoElement.videoWidth;
                        canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

                        const imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);
                        const code = jsQR(imageData.data, canvasElement.width, canvasElement.height);

                        if (code) {
                            // resultElement.textContent = "QR Code Detected: " + code.data;
                            checkBookIsbn(code.data);

                            // Disable scanning for 10 seconds
                            canScan = false;
                            setTimeout(() => {
                                canScan = true; // Enable scanning after 10 seconds
                            }, 10000); // 10 seconds delay
                        }
                    }
                    requestAnimationFrame(scanQRCode);
                }

                document.addEventListener('keydown', function (event) {
                    // Assuming the scanner sends the QR code as keyboard input
                    // We accumulate the input to build the complete code
                    if (event.key !== "Shift" && event.key !== "Enter" && event.key !== "Tab") {
                        scannedData += event.key; // Add the character to the scanned data
                    }
                    
                    // If the scanner sends a newline (enter) after scanning, process the data
                    if (event.key === "Enter") {
                        checkBookIsbn(scannedData);
                        scannedData = ""; // Clear the scanned data after processing
                    }
                });



                
                function checkBookIsbn(data) {
                    const url = 'check_returned_books.php';

                    const postData = new URLSearchParams();
                    postData.append('qr_data', data);

                    // Send the AJAX request using fetch
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: postData
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            if (responseData.status === 1) {

                                var bookData = responseData.data;

                                id = responseData.data[0].request_id;

                                Swal.fire({
                                    title: "Success",
                                    icon: "success"
                                });

                                sendToForm(bookData);

                                disableSubmit();

                            } else if (responseData.status === 2) {

                                Swal.fire({
                                    title: "Error",
                                    text: "No book request found in the system.",
                                    icon: "error"
                                });

                            } else if (responseData.status === 3) {

                                Swal.fire({
                                    title: "Error",
                                    icon: "error"
                                });

                            } else {
                                // Unexpected response
                                console.log("Unexpected response:", responseData);
                            }
                        })
                        .catch(error => {
                            // Handle any errors that occur during the request
                            console.error('Error:', error);
                        });
                }

                function sendToForm(data) {

                    document.querySelector('input[name="name"]').value = data[0].name || '';
                    document.querySelector('input[name="lrn"]').value = data[0].lrn || '';
                    document.querySelector('input[name="isbn"]').value = data[0].isbn || '';
                    document.querySelector('input[name="request-date"]').value = data[0].request_date || '';


                    document.querySelector('#confirm-book-isbn').textContent = data[0].isbn || '';
                    document.querySelector('#confirm-book-title').textContent = data[0].title || '';
                    document.querySelector('#confirm-book-author').textContent = data[0].author || '';

                }

                function saveReturn(id) {
                    const url = 'save_returned_books.php';

                    const returnDate = document.querySelector('#return-date').value;

                    if (!returnDate) {
                        Swal.fire({
                            title: "Missing Date",
                            text: "Please select a valid return date.",
                            icon: "error"
                        });
                        return;
                    }

                    const postData = new URLSearchParams();
                    postData.append('id', id);
                    postData.append('return_date', returnDate);

                    // Send the AJAX request using fetch
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: postData
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            if (responseData.status === 1) {

                                var bookData = responseData.data;

                                Swal.fire({
                                    title: "Success",
                                    text: "Book has been returned.",
                                    icon: "success"
                                });

                                sendToForm(bookData);

                                disableSubmit();

                            } else if (responseData.status === 2) {

                                Swal.fire({
                                    title: "Error",
                                    text: "There is an error returning the book.",
                                    icon: "error"
                                });

                            } else if (responseData.status === 3) {

                                Swal.fire({
                                    title: "Error",
                                    icon: "error"
                                });

                            } else {
                                // Unexpected response
                                console.log("Unexpected response:", responseData);
                            }
                        })
                        .catch(error => {
                            // Handle any errors that occur during the request
                            console.error('Error:', error);
                        });
                }

                let id = null;

                $("#confirm-submit").click(function() {

                    $('#confirmation-modal').modal('hide');

                    saveReturn(id);

                    $('#form')[0].reset();

                });

                function disableSubmit() {
                    let name = document.querySelector('input[name="name"]').value;

                    if (name == '' || name == null) {
                        $('#submit-return').prop('disabled', true); // Disable the button
                    } else {
                        $('#submit-return').prop('disabled', false); // Enable the button
                    }
                }


                disableSubmit();
            });
        </script>
    </div>
</div>
