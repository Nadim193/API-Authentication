<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Product Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1>Product Form</h1>
    <form id="productForm" action="{{route('products.submit')}}" enctype="multipart/form-data" method="POST">
        @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" id="name" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="purchase_price" class="form-label">Purchase Price:</label>
        <input type="number" id="purchase_price" name="purchase_price" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="sales_price" class="form-label">Sales Price:</label>
        <input type="number" id="sales_price" name="sales_price" step="0.01" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="color" class="form-label">Color:</label>
        <input type="text" id="color" name="color[]" class="form-control" required>
        <button type="button" id="addColor" class="btn btn-primary mt-2">Add Color</button>
      </div>
      <div class="mb-3">
        <label for="size" class="form-label">Size:</label>
        <input type="text" id="size" name="size[]" class="form-control" required>
        <button type="button" id="addSize" class="btn btn-primary mt-2">Add Size</button>
      </div>
      <div class="mb-3">
        <label for="images" class="form-label">Images:</label>
        <input type="file" id="images" name="images[]" accept="image/*" class="form-control" multiple required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <a href="{{route('products.index')}}" class="btn btn-primary mt-2">Back</a>
    <br>
    <hr>
    <div id="imageContainer">
          <!-- Existing image preview elements (if any) will be dynamically added here -->
      </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    
    <script>
        $(document).ready(function () {
            // Image preview function
            function previewImages(input, container) {
                if (input.files && input.files.length > 0) {
                    $(container).html(""); // Clear previous previews

                    Array.from(input.files).forEach(function (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
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
            $("#addColor").click(function () {
            $('<input type="text" name="color[]" class="form-control" required>').insertBefore(this);
            });

            // Add size button event
            $("#addSize").click(function () {
            $('<input type="text" name="size[]" class="form-control" required>').insertBefore(this);
            });

            // Image input change event
            $("#images").change(function () {
            previewImages(this, "#imageContainer");
            });

            // Form submit event
            $("#productForm").validate({
                rules: {
                    images: {
                    required: true,
                    accept: "image/*",
                    },
                },
                messages: {
                    images: {
                    required: "Please select at least one image file.",
                    accept: "Only image files are allowed.",
                    },
                },
                submitHandler: function (form, event) {
                    event.preventDefault();

                    let formData = new FormData(form);

                    const totalImages = $("#images")[0].files.length;
                    let images = $("#images")[0];

                    for (let i = 0; i < totalImages; i++) {
                    formData.append("images" + i, images.files[i]);
                    }
                    formData.append("totalImages", totalImages);

                    console.log(formData);

                    try {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('products.submit') }}",
                        data: formData,
                        processData: false,
                        cache: false,
                        contentType: false,
                        success: function (response) {
                        alert(response.message);
                        window.location.href = "{{ route('products.index') }}";
                        },
                        error: function (xhr, status, error) {
                        alert("An error occurred while saving the product.");
                        },
                    });
                    } catch (error) {
                        console.log(error);
                    }
                },
                });
        });
    </script>

</body>
</html>
