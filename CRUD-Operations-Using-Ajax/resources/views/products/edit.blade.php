<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Product Form</h1>
        <form id="productForm" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data"
            method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required value="{{ $product->name }}">
            </div>
            <div class="mb-3">
                <label for="purchase_price" class="form-label">Purchase Price:</label>
                <input type="number" id="purchase_price" name="purchase_price" step="0.01" class="form-control" required
                    value="{{ $product->purchase_price }}">
            </div>
            <div class="mb-3">
                <label for="sales_price" class="form-label">Sales Price:</label>
                <input type="number" id="sales_price" name="sales_price" step="0.01" class="form-control" required
                    value="{{ $product->sales_price }}">
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color:</label>
                @foreach (json_decode($product->color) as $color)
                <input type="text" name="color[]" class="form-control" required value="{{ $color }}">
                @endforeach
                <button type="button" id="addColor" class="btn btn-primary mt-2">Add Color</button>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Size:</label>
                @foreach (json_decode($product->size) as $size)
                <input type="text" name="size[]" class="form-control" required value="{{ $size }}">
                @endforeach
                <button type="button" id="addSize" class="btn btn-primary mt-2">Add Size</button>
            </div>

            <!-- Existing images -->
            <div id="existingImageContainer">
                @foreach (json_decode($product->images) as $image)
                <div class="existing-image">
                    <img src="{{ asset('images/' . $image) }}" alt="Product Image" style="width: 50px; height: 50px;">
                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                    <button type="button" class="btn btn-danger remove-image">Remove</button>
                </div>
                @endforeach
            </div>

            <!-- Container for removed images -->
            <div id="removedImagesContainer"></div>


            <!-- Add new images -->
            <div id="newImageContainer">
                <div class="mb-3">
                    <label for="images" class="form-label">Add Images:</label>
                    <input type="file" id="images" name="images[]" accept="image/*" class="form-control" multiple>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Back</a>
    </div>
    <hr>
    <br>

      <div id="imageContainer">
          <!-- Existing image preview elements (if any) will be dynamically added here -->
      </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
        integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    $(document).ready(function() {
        // Image preview function
        function previewImages(input, container) {
            if (input.files && input.files.length > 0) {
                $(container).html(""); // Clear previous previews

                Array.from(input.files).forEach(function(file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(container).append(
                            '<img src="' + e.target.result + '" alt="Image Preview" />'
                        );
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        // Add color button event
        $("#addColor").click(function() {
            $('<input type="text" name="color[]" class="form-control" required>').insertBefore(this);
        });

        // Add size button event
        $("#addSize").click(function() {
            $('<input type="text" name="size[]" class="form-control" required>').insertBefore(this);
        });

        // Remove image button event
        $(document).on("click", ".remove-image", function() {
            $(this).closest(".existing-image").remove();
        });

        // Image input change event
        $("#images").change(function() {
            previewImages(this, "#imageContainer");
        });

        // Remove image button event
        $(document).on("click", ".remove-image", function() {
            const existingImageContainer = $(this).closest(".existing-image");
            const imageInput = existingImageContainer.find("input[name^='existing_images']");
            const removedImagesContainer = $("#removedImagesContainer");

            // Move the removed image input to the removed images container
            removedImagesContainer.append(imageInput);

            // Hide the existing image container
            existingImageContainer.hide();
        });

        // Form submit event
        $("#productForm").submit(function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            // Append new images
            const totalImages = $("#images")[0].files.length;
            let images = $("#images")[0];

            for (let i = 0; i < totalImages; i++) {
                formData.append('images' + i, images.files[i]);
            }

            // Append the removed images to the form data
            const removedImages = [];
            $("#removedImagesContainer input[name^='existing_images']").each(function() {
                removedImages.push($(this).val());
            });
            formData.append("removed_images", JSON.stringify(removedImages));


            try {
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: formData,
                    processData: false,
                    cache: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ route('products.index') }}";
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            "An error occurred while updating the product.";
                        alert(errorMessage);
                    },
                });
            } catch (error) {
                console.log(error);
            }
        });
    });
    </script>

</body>

</html>