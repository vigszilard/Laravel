@php use App\Models\Amendment; @endphp

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
              integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <base href="http://localhost:63342/newspaper/">
        <title>Newspaper | Dashboard</title>
    </head>

    <body>
        @include('components.header')
        @include('components.error_toast')

        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-md-12">
                    <h2>Dashboard</h2>
                    <h5 class="text-muted">Welcome, {{ $user["firstname"] }}</h5>
                </div>
            </div>

            @if ($user["role_id"] == 3)
                <div class="row mt-4">
                    @foreach ($declinedArticles as $article)
                        @php $amendment = (new Amendment)->getAmendmentByArticleId($article["id"]); @endphp
                        @if (!$amendment)
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $article["title"] }}</h5>
                                        <p class="card-text flex-grow-1 text-justify">{{ $article["content"] }}</p>

                                        <div class="d-flex justify-content-between">
                                            <form action="{{ route('approveArticle') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="article_id" value="{{ $article["id"] }}">
                                                <button type="submit" class="btn btn-primary">Approve</button>
                                            </form>

                                            <form action="{{ route('declineArticle') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="article_id" value="{{ $article["id"] }}">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#amendmentModal" data-article-id="{{ $article["id"] }}"
                                                        onclick="setArticleId({{ $article["id"] }})">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div id="accordion" class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" id="headingAmendments">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseAmendments" aria-expanded="false"
                                            aria-controls="collapseAmendments">
                                        <i class="fas fa-chevron-down"></i>
                                        Waiting for changes...
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseAmendments" class="collapse" aria-labelledby="headingAmendments"
                                 data-parent="#accordion">
                                <div class="row mt-4">
                                    @foreach ($declinedArticles as $article)
                                        @php $amendment = (new Amendment)->getAmendmentByArticleId($article["id"]); @endphp
                                        @if ($amendment)
                                            <div class="col-md-6 p-3">
                                                <div class="card mb-4">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">{{ $article["title"] }}</h5>
                                                        <div class="d-flex justify-content-center">
                                                            <form action="" method="post">
                                                                <input type="hidden" name="article_id"
                                                                       value="{{ $article["id"] }}">
                                                                <button type="button" class="btn btn-primary show-amendments"
                                                                        data-toggle="modal" data-target="#amendmentModal"
                                                                        data-article-id="{{ $article["id"] }}"
                                                                        data-amendment-details="{{ json_encode($amendment) }}">
                                                                    Show amendments
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($user["role_id"] == 2)
                <div class="row justify-content-center mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Submit Article</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('submitArticle') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input id="title" type="text" class="form-control" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="content">Content:</label>
                                        <textarea id="content" class="form-control" name="content" rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="category_id">Category:</label>
                                        <select id="category_id" class="form-control" name="category_id">
                                            @foreach ($allCategories as $category)
                                                <option value="{{ $category["id"] }}">
                                                    {{ $category["name"] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Submit Article</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-3">
                        <h5 class="text-muted">My Articles</h5>
                    </div>
                    <div class="col-md-12">
                        <div id="articleCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner text-center">
                                @foreach ($writerArticles as $index => $each_article)
                                    <div class="carousel-item {{ $index === 0 ? "active" : "" }}">
                                        <div class="card-deck">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $each_article["title"] }}</h5>
                                                    <p class="card-text flex-grow-1 text-justify">
                                                        {{ get_substring($each_article["content"]) }}
                                                    </p>
                                                    @php
                                                        $amendment = (new Amendment)->getAmendmentByArticleId($each_article["id"]);
                                                        $status = $each_article["is_approved"] == 1 ? "Article approved" :
                                                            ($amendment ? "Changes required" : "Waiting for approval");

                                                        $statusClass = $each_article["is_approved"] == 1 ? "text-success" : ($amendment ? "text-warning" : "text-secondary");
                                                    @endphp
                                                    <p class="font-weight-bold">
                                                        <span class="{{ $statusClass }}">{{ $status }}</span>
                                                    </p>
                                                    @if($status == "Changes required")
                                                        <div class="d-flex justify-content-center">
                                                            <div class="col-md-6">
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="article_id"
                                                                           value="{{ $each_article["id"] }}">
                                                                    <button type="button"
                                                                            class="btn btn-primary show-amendments"
                                                                            data-toggle="modal" data-target="#amendmentModal"
                                                                            data-article-id="{{ $each_article["id"] }}"
                                                                            data-amendment-details="{{ json_encode($amendment) }}">
                                                                        Show amendments
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="article_id"
                                                                           value="{{ $each_article["id"] }}">
                                                                    <button type="button" class="btn btn-primary"
                                                                            data-toggle="modal"
                                                                            data-target="#submitArticleModal"
                                                                            data-amendment-id="{{ $amendment["id"] }}"
                                                                            data-article-details="{{ json_encode($each_article) }}">
                                                                        Update Article
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#articleCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#articleCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal for writing amendments -->
        <div class="modal fade" id="amendmentModal" tabindex="-1" role="dialog" aria-labelledby="amendmentModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="amendmentModalLabel">Amendments</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="amendmentForm" action="{{ route('declineArticle') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="amendmentText" class="col-sm-3 col-form-label">Amendments:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="amendmentText" name="text" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" id="modalArticleId" name="article_id" value="">
                                    <input type="hidden" id="amendmentDetails" name="amendment_details" value="">
                                    <button id="submitBtn" type="submit" class="btn btn-primary btn-block"
                                            onclick="getArticleId()">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('components.updateArticleForm')
        @include('components.footer')

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
    </body>
</html>



