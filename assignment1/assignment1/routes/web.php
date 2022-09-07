<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $sql = 'select * from watches';
    $watches  = DB::select($sql);
    foreach ($watches as $watch) {
        $avgSentiment = get_sentiment_score($watch->id);
        $watch->avgSentiment = $avgSentiment;
        $avgRating = get_avg_rating($watch->id);
        $watch->avgRating = $avgRating->avgRating;
    };
    $sortByRating = request('sortByRating');
    $sortBySentiment = request('sortBySentiment');
    $asc = request('asc');
    if ($sortByRating) {
        $watches = sort_rating($watches, $asc);
        };
    if ($sortBySentiment) {
        $watches = sort_sentiment($watches, $asc);
        };
    return view('watches.watches_list')->with('watches', $watches)->with('sortByRating', $sortByRating)->with('sortBySentiment', $sortBySentiment);
});

Route::get('watch_details/{watchId}', function ($watchId) {
    $watch = get_watch($watchId);
    $reviewsAndCount = get_reviews($watchId);
    $reviews = $reviewsAndCount[0];
    $count = $reviewsAndCount[1];
    return view('watches.watch_details')->with('watch', $watch)->with('reviews', $reviews)->with('count', $count);
});

Route::get('add_watch', function () {
    return view('watches.add_watch');
});

Route::post('add_watch_action', function () {
    $make = request('make');
    $model = request('model');
    $details = request('details');
    if (!$make || !$model || !$details) {
        $errorMessage = 'Missing value. Make, model and details are all required.';
        return view('error.error')->with('errorMessage', $errorMessage);
    };
    if (validate_new_watch($make, $model)) {
        $watchId = add_watch($make, $model, $details);
        if($watchId){
            return redirect("watch_details/$watchId");
        } else {
            $errorMessage = 'Error while adding item to database.';
            return view('error.error')->with('errorMessage', $errorMessage);
        };
    } else {
        $errorMessage = 'Watch is already in the database. Uable to add watch.';
        return view('error.error')->with('errorMessage', $errorMessage);
    };
});

Route::get('delete_watch/{watchId}', function ($watchId) {
    delete_watch($watchId);
    return redirect("/");
});

Route::get('update_watch/{watchId}', function ($watchId) {
    $watch = get_watch($watchId);
    return view('watches.update_watch')->with('watch', $watch);
});

Route::post('update_watch_action', function () {
    $make = request('make');
    $model = request('model');
    $details = request('details');
    if (!$make || !$model || !$details) {
        $errorMessage = 'Missing value. Make, model and details are all required.';
        return view('error.error')->with('errorMessage', $errorMessage);
    };
    $watchId = request('id');
    update_watch($watchId, $make, $model, $details);
    return redirect("watch_details/$watchId");
});

Route::get('add_review/{watchId}', function ($watchId) {
    $watch = get_watch($watchId);
    return view('reviews.add_review')->with('watch', $watch);
});

Route::post('add_review_action', function () {
    $watchId = request('id');
    $user = request('user');
    $rating = request('rating');
    $reviewText = request('reviewText');
    if (!$user || !$rating || !$reviewText) {
        $errorMessage = 'Missing value. User, rating and review text are all required.';
        return view('error.error')->with('errorMessage', $errorMessage);
    };
    $added = add_review($watchId, $user, $rating, $reviewText);
    if ($added) {
        return redirect("watch_details/$watchId");
    } else {
        $userUpper = ucwords($user);
        $errorMessage = "User $userUpper has already reviewed this watch. Only one review per user for each watch allowed.";
        return view('error.error')->with('errorMessage', $errorMessage);
    }
});

Route::get('edit_review/{reviewId}', function ($reviewId) {
    $review = get_review($reviewId);
    return view('reviews.edit_review')->with('review', $review);
});

Route::post('edit_review_action', function () {
    $reviewId = request('reviewId');
    $user = request('user');
    $rating = request('rating');
    $reviewText = request('reviewText');
    if (!$user || !$rating || !$reviewText) {
        $errorMessage = 'Missing value. User, rating and review text are all required.';
        return view('error.error')->with('errorMessage', $errorMessage);
    };
    $watchId = edit_review($reviewId, $user, $rating, $reviewText);
    return redirect("watch_details/$watchId");
});

Route::get('delete_review/{reviewId}', function ($reviewId) {
    $watchId = DB::select("select watchId from reviews where id = ?", array($reviewId));
    $watchId = $watchId[0]->watchId;
    delete_review($reviewId);
    return redirect("watch_details/$watchId");
});

Route::get('watches/makes', function () {
    $sql = 'select distinct(make) from watches';
    $makes  = DB::select($sql);
    return view('watches.makes')->with('makes', $makes);
});

Route::get('make/{make}', function ($make) {
    $sql = 'select * from watches where make like ?';
    $watches  = DB::select($sql, array("$make"));
    return view("watches.make")->with('watches', $watches)->with('make', $make);
});

Route::get('search_action', function () {
    $query = request('query');
    $sql1 = "select * from watches where make like ? or model like ?";
    $sql2 = "select watches.id, watches.make, watches.model from watches, reviews where reviews.watchId = watches.id and reviews.user like ?";
    $watchSearch = DB::select($sql1, array("%$query%", "%$query%"));
    $reviewSearch = DB::select($sql2, array("$query"));
    if (!$watchSearch && $reviewSearch) {
        return view("watches.search")->with('watches', $reviewSearch);
    } else {
        return view("watches.search")->with('watches', $watchSearch);
    };
});







/* Adds a new watch to the watches table with a specified make (formatted capitalised, type: str), 
model (type: str) and details (type: str). Returns the id of the watch that was just added (type: str). */
function add_watch($make, $model, $details){
    $sql = "insert into watches (make, model, details) values (?, ?, ?)";
    DB::insert($sql, array(ucwords($make), $model, $details));
    $watchId = DB::getPdo()->lastInsertId();
    return($watchId);
};

/* Checks if there already is a watch with the same make AND model in the watches table using inputs for
make (type: str) and model (type: str). Returns boolean value true if there is no watch with make model 
combination in table already, else returns false. */
function validate_new_watch($make, $model){
    $modelsArr = [];
    $sql = "select model from watches where make like ?";
    $models = DB::select($sql, array("$make"));
    foreach ($models as $m) {
        array_push($modelsArr, strtolower($m->model));
    };
    if (!in_array(strtolower($model), $modelsArr)) {
        return true;
    } else {
        return false;
    };
}

/* Updates a watch entry in the watches table based on the watch id. Takes parameters for id (type: str), 
make (type: str), model (type: str), and details (type: str). */
function update_watch($watchId, $make, $model, $details){
    $sql = "update watches set make = ?, model = ?, details = ? where id = ?";
    DB::update($sql, array($make, $model, $details, $watchId));
};

/* Deletes a watch and all related reviews from watches table and reviews table. Requires a watch id (type: str) 
as an input paramteter. */
function delete_watch($watchId){
    $reviews = get_reviews($watchId)[0];
    foreach ($reviews as $review) {
        $sql1 = "delete from reviews where id = ?";
        DB::delete($sql1, array($review->id));
    };
    $sql2 = "delete from watches where id = ?";
    DB::delete($sql2, array($watchId));
};

/* Function for retrieving the all attributes of a single watch from the watches table. Takes a 
watch id (type: str) as an input parameter and returns an object containing all the attributes for the watch 
with the relevant id. */
function get_watch($watchId){
    $sql = "select * from watches where id = ?";
    $watches = DB::select($sql, array($watchId));
    if (count($watches) != 1){
        die("Something went wrong. Invalid query or result: $sql $watchId");
    } else {
        $watch = $watches[0];
        return $watch;
    };
};

/* Function for retrieving all reviews for a watch and the total number of reviews for that watch. Takes
a watch id (type: str) as the input parameter and returns an array where index 0 is an array containing objects for 
all reviews for that watch and index 1 is the total number of reviews for that watch as type integer. */
function get_reviews($watchId){
    $sql = "select id, user, rating, reviewText from reviews where watchId = ?";
    $reviews = DB::select($sql, array($watchId));
    $count = count($reviews);
    return array($reviews, $count);
};

/* Function for retrieving all attributes of a single review with a given id. The function takes the review
id (type: str) as the input parameter, and returns an object containing all the attributes of that review. */
function get_review($reviewId){
    $sql = "select * from reviews where id = ?";
    $review = DB::select($sql, array($reviewId));
    return $review[0];
};

/* Function for inserting a new review into the review table. The function takes the input parameters watch 
id (type: str), user (type: str), rating (type: str), and review text (type: str). Returns true (type: boolean) 
if reviewwas added and false (type: boolean) if not added. */
function add_review($watchId, $user, $rating, $reviewText){
    if(!reviewed_watch_before ($watchId, $user)) {
        $sql = "insert into reviews (user, rating, reviewText, watchId) values (?, ?, ?, ?)";
        DB::insert($sql, array($user, $rating, $reviewText, $watchId));
        $reviewId = DB::getPdo()->lastInsertId();
        return true;
    } else {
        return false;
    };
};

/* Function that checks if a specific user has a review for a particular watch already. The function takes a
watch id (type: str) and a user (type: str) as input parameters, and returns true (type: boolean) if the
user has a review for the watch already, and false (type: boolean) if the user does not. */
function reviewed_watch_before ($watchId, $user) {
    $reviewers = [];
    $reviews = get_reviews($watchId)[0];
    foreach ($reviews as $review) {
        array_push($reviewers, strtolower($review->user));
    };
    if (in_array(strtolower($user), $reviewers)) {
        return true;
    } else {
        return false;
    };
};

/* Function that updates the attributes of a review in the reviews table. The function takes a review id
(type: str), user (type: str), rating (type: str), and review text (type: str) as input parameters. The watch
id (type: str) for which the relevant review is updated is returned. */
function edit_review($reviewId, $user, $rating, $reviewText){
    $sql1 = "update reviews set user = ?, rating = ?, reviewText = ? where id = ?";
    DB::update($sql1, array($user, $rating, $reviewText, $reviewId));
    $sql2 = "select watchId from reviews where id = ?";
    $arrWatchId = DB::select($sql2, array($reviewId));
    $watchId = $arrWatchId[0]->watchId;
    return $watchId;
};

/* Function for deleting a review from the reviews table. The function takes the review id (type: str) as the 
input parameter. */
function delete_review($reviewId){
    $sql = "delete from reviews where id = ?";
    DB::delete($sql, array($reviewId));
};

/* Function for returning the average review rating for a watch. The function takes the watch id (type: str)
as the input parameter and returns an object with the average review rating for that watch as an attribute. */
function get_avg_rating($watchId) {
    $sql = "select avg(rating) as avgRating from reviews where reviews.watchId = ?";
    $watchAvgRating = DB::select($sql, array($watchId))[0];
    return $watchAvgRating;
};

/* Function that sorts an array of watches either in ascending or descending order by average review rating.
The function takes an array of watches (objects) and asc(ending) (type: boolean) as input parameters. The sorted
array of watches is returned. */
function sort_rating($watches, $asc) {
    if (!$asc) {
        usort($watches, function($watch1, $watch2){
            return $watch1->avgRating < $watch2->avgRating;
        });
    } else{
        usort($watches, function($watch1, $watch2){
            return $watch1->avgRating > $watch2->avgRating;
        }); 
    };
    return $watches;
};

/* Function that calculates the sentiment score of a particular watch. The function takes a watch id (type: str)
as the input parameter and returns a sentiment score (type: float). */
function get_sentiment_score($watchId) {
    $pos = ['great', 'perfect', 'good', 'excellent', 'beautiful', 'style', 'accurate', 'amazing', 'love', 'meticulous', 'comfortable', 'light', 'beaming', 'happy', 'ecstatic', 'enchanting', 'exquisite', 'marvelous', 'robust', 'inexpensive'];
    $neg = ['poor', 'bad', 'inaccurate', 'ugly', 'nosy', 'uncomfortable', 'disappointed', 'atrocious', 'pathetic', 'detrimental', 'dreadful', 'ghastly', 'grotesque', 'stupid', 'monstrous', 'offensive', 'repulsive', 'expensive', 'cheap'];
    $score = 0;
    $reviews = get_reviews($watchId)[0];
    $reviewCount = get_reviews($watchId)[1];
    foreach ($reviews as $review) {
        $text = strtolower(preg_replace("#[[:punct:]]#", "", $review->reviewText));
        $text = explode(' ', $text);
        foreach ($text as $word) {
            if (in_array($word, $pos)) {
                $score += 1;
            } elseif (in_array($word, $neg)) {
                $score -= 1;
            }
        };
    };
    if ($reviewCount == 0) {
        $reviewCount = 1;
    };
    $score = $score/$reviewCount;
    return $score;
};

/* Function that sorts an array of watches either in ascending or descending order by sentiment score.
The function takes an array of watches (objects) and asc(ending) (type: boolean) as input parameters. The sorted
array of watches is returned. */
function sort_sentiment($watches, $asc) {
    if (!$asc) {
        usort($watches, function($watch1, $watch2){
            return $watch1->avgSentiment < $watch2->avgSentiment;
        });
    } else{
        usort($watches, function($watch1, $watch2){
            return $watch1->avgSentiment > $watch2->avgSentiment;
        }); 
    };
    return $watches;
};