<!-- resources/views/article.blade.php -->

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
              integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <base href="{{ url('/') }}">
        <title>Newspaper | {{ $articleData["title"] }}</title>
    </head>

    <body>
        @include('components.header')
        @include('components.error_toast')

        <a class="ml-5 mt-5 btn-link" href="{{ url('/') }}">
            <i class="fas fa-chevron-left"></i> Back
        </a>

        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h2 class="text-center mb-4">{{ $articleData["title"] }}</h2>
                    <div class="justify-content-between">
                        <h4 class="text-muted">Category - {{ $categoryData["name"] }}</h4>
                        <p class="text-muted justify-content-between">
                            {{ $journalistData["firstname"] . " " . $journalistData["lastname"] }} |
                            {{ format_timestamp($articleData["created_at"]) ?? "Unknown" }}
                        </p>
                    </div>
                    <p class="text-justify">
                        {{ $articleData["content"] }}
                    </p>
                </div>
            </div>
        </div>

        @include('components.footer')

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>

    </body>

</html>
