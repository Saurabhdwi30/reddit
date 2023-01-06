<?php
require_once("reddit.php");


$obj = new reddit();

// to keep result data
$result_array = [];

//limit for dataset while calling api end point
$limit = 100;
$page = 1;

//get next records after id
$after = '';

while($page <=3){
	// echo 'page '. $page;
	++$page;
	$data = $obj->getListing($limit, $after);

	foreach($data['data']['children'] as $children){
	
		$words_frequency_for_title = array_count_values(explode(' ',$children['data']['title']));
		if(!empty($words_frequency_for_title)){
			foreach($words_frequency_for_title as $word => $frequency){
				
				$clean_word = $word;

				//Check if word has question mark then remove the same
				if (preg_match('/[?]/', $word))
				{
					$clean_word = substr($word, 0, -1);
				}

				if(array_key_exists($word, $result_array)){
					$result_array[$clean_word] += $words_frequency_for_title[$word];
				}else{
					$result_array[$clean_word] = $words_frequency_for_title[$word];
				}
			}
		}
		$after = $children['data']['name'];
	}
}
//sorting by frequency
arsort($result_array);

// getting top 300 words
$top_3hundred_words = array_slice($result_array, 0, 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
.keyword {
    margin-right: 3px;
    margin-bottom: 6px;
}

.keyword, .subreddit, .subreddit-noexist, .subreddit-selected, .subreddit-tag, .subreddit-tag-selected {
    border-width: 1px;
    border-style: solid;
    border-radius: 5px;
    padding: 3px 8px;
    display: inline-block;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    cursor: pointer;
}

</style>
<body>

<div class="text-center">
  <h1>&nbsp;</h1>
</div>
  
<div class="container" style="background-color: rgb(0 123 255 / 25%)">
  <div class="row">
		<div class="row pt-5 px-sm-5">
        <div class="col-12 pb-5 data-wrap">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="open-sans">
                    100 most used words ordered by the frequency at which they appear in the titles of 300 most upvoted posts of all time on r/askreddit
                    <!-- <span class="thin selector active">keywords</span>
                    <span class="thin dark-gray">/</span>
                    <span class="thin selector">users</span> -->
                </h2>
            </div>
            <div class="data-container mt-3" id="related-subreddits-keywords">
				<?php
					foreach($top_3hundred_words as $word => $frequency){
						echo '<a href="subreddit-analysis/Phatasswhitegirls" class="keyword ">'.$word.' ('.$frequency.')</a>';
					}
				?>
			</div>
		</div>
		</div>
  </div>
</div>

</body>
</html>