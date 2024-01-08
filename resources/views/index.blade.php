<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <base href="http://localhost:63342/newspaper/">
        <title>Newspaper</title>
    </head>
    <body>
        @include('components.header')
        @include('components.error_toast')
        <div class="container mt-5 mb-5 text-center">
            <ul class="nav nav-tabs mb-4" id="myTabs">
                <li class="nav-item">
                    <a class="nav-link active text-dark" id="all-tab" data-toggle="tab" href="#all">All Articles</a>
                </li>
                @foreach ($all_categories as $category)
                    <li class="nav-item">
                        <a class="nav-link text-dark" id="category-{{ $category['id'] }}-tab" data-toggle="tab" href="#category-{{ $category['id'] }}">
                            {{ $category['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all">
                    <div class="row justify-content-center">
                        @if (empty($all_articles))
                            <div class="col-md-6 text-center">
                                <p>No articles available</p>
                            </div>
                        @else
                            @foreach ($all_articles as $article)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body text-center d-flex flex-column">
                                            <h5 class="card-title">
                                                {{ $article['title'] }}
                                            </h5>
                                            <div class="d-flex justify-content-between">
                                                    <span class="card-subtitle mb-2 text-muted font-italic py-2">
                                                        {{ $journalists->getJournalistById($article['author_id'])['firstname'] }} {{ $journalists->getJournalistById($article['author_id'])['lastname'] }}
                                                    </span>
                                                <span class="card-subtitle mb-2 text-muted font-italic py-2">
                                                        {{ format_timestamp($article['created_at']) }}
                                                    </span>
                                            </div>
                                            <p class="card-text flex-grow-1 text-justify">
                                                {{ get_substring($article['content']) }}
                                            </p>

                                            @php
                                                session(['article_id' => $article['id']]);
                                                $isLoggedIn = auth()->check();
                                                $routeParameters = ['id' => $article['id']];
                                                $modal_attributes = $isLoggedIn ? '' : "data-toggle=modal data-target=#readMoreModal data-article-id={$article['id']}";
                                                $button_action = $isLoggedIn ? "window.location.href='".route('article', $routeParameters)."'" : "setArticleId({$article['id']})";
                                            @endphp
                                            <button type="button" class="btn btn-primary mt-auto" {{ $modal_attributes }} onclick="{{ $button_action }}">
                                                Read More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                @foreach ($all_categories as $category)
                    <div class="tab-pane fade" id="category-{{ $category['id'] }}">
                        <div class="row justify-content-center">
                            @if (isset($articles_by_category[$category['id']]))
                                @foreach ($articles_by_category[$category['id']] as $article)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center d-flex flex-column">
                                                <h5 class="card-title">
                                                    {{ $article['title'] }}
                                                </h5>
                                                <div class="d-flex justify-content-between">
                                                        <span class="card-subtitle mb-2 text-muted font-italic py-2">
                                                            {{ $journalists->getJournalistById($article['author_id'])['firstname'] }} {{ $journalists->getJournalistById($article['author_id'])['lastname'] }}
                                                        </span>
                                                    <span class="card-subtitle mb-2 text-muted font-italic py-2">
                                                            {{ format_timestamp($article['created_at']) }}
                                                        </span>
                                                </div>
                                                <p class="card-text flex-grow-1 text-justify">
                                                    {{ get_substring($article['content']) }}
                                                </p>

                                                @php
                                                    session(['article_id' => $article['id']]);
                                                    $isLoggedIn = auth()->check();
                                                    $routeParameters = ['id' => $article['id']];
                                                    $modal_attributes = $isLoggedIn ? '' : "data-toggle=modal data-target=#readMoreModal data-article-id={$article['id']}";
                                                    $button_action = $isLoggedIn ? "window.location.href='".route('article', $routeParameters)."'" : "setArticleId({$article['id']})";
                                                @endphp
                                                <button type="button" class="btn btn-primary mt-auto" {{ $modal_attributes }} onclick="{{ $button_action }}">
                                                    Read More
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-6 text-center">
                                    <p>No articles in this category</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @include('components.footer')
        @include('components.modal')

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
    </body>
</html>
