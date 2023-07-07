<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
     <!-- Font-awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>

<div class="container" style="margin-top: 50px;">

    <a href="/create" class="btn btn-outline-success">Add New Product</a>

            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Title</th>
                    <th>Purchase Price</th>
                    <th>Sales Price</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Images</th>
                    <th colspan="3">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td>{{$product->name}}</td>
                            <td>${{$product->purchase_price}}</td>
                            <td>${{$product->sales_price}}</td>
                            <td>
                                @foreach (json_decode($product->color) as $color)
                                    {{$color}},
                                @endforeach
                            </td>
                            <td>
                                @foreach (json_decode($product->size) as $size)
                                    {{$size}},
                                @endforeach
                            </td>
                            <td>
                                @php
                                $images = json_decode($product->images);
                                if (is_array($images) || is_object($images)) {
                                    foreach ($images as $image) {
                                        echo '<img src="' . asset('images/' . $image) . '" alt="Product Image" style="width: 50px; height: 50px;">';
                                    }
                                }
                                @endphp
                            </td>
                            <td>
                                <form method="POST" id="edit-product" action="{{ route('products.edit', $product->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <button type='submit'  class="btn btn-outline-primary" data-toggle="tooltip">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <form method="POST" id="delete-product" action="{{ route('products.delete', $product->id) }}">
                                    @csrf
                                    @method('delete')

                                    <button type='submit'  class="btn btn-outline-danger" data-toggle="tooltip">
                                    <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No products yet!</td>
                        </tr>
                    @endforelse
                </tbody>
              </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <script>
    $(document).ready(function () {
        // Delete product
        $(document).on('click', '#delete-product', function () {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "/delete/" + productId, // Replace with your delete route
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function (response) {
                    alert(response.message);
                    // Refresh the page or update the product list
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("An error occurred while deleting the product.");
                }
            });
        });
    });
</script>
</body>
</html>