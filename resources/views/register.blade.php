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
        <base href="http://localhost:63342/newspaper/">
        <title>Newspaper | Register</title>
    </head>

    <body>
        @include('components.header')
        @include('components.error_toast')

        <a class="ml-5 mt-5 btn-link" href="{{ url('/') }}">
            <i class="fas fa-chevron-left"></i> Back
        </a>

        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">User Registration</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('register') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input id="email" type="email" class="form-control" name="email" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="firstname">First Name:</label>
                                    <input id="firstname" type="text" class="form-control" name="firstname" required>
                                </div>

                                <div class="form-group">
                                    <label for="lastname">Last Name:</label>
                                    <input id="lastname" type="text" class="form-control" name="lastname" required>
                                </div>

                                <div class="form-group">
                                    <label for="role_id">Role:</label>
                                    <select id="role_id" class="form-control" name="role_id">
                                        <option value="1">
                                            Reader
                                        </option>
                                        <option value="2">
                                            Writer
                                        </option>
                                        <option value="3">
                                            Editor
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="article_id" id="article_id" value="">
                                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
