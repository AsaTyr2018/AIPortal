<?php
$imagesDir = './images/';
$categories = array_diff(scandir($imagesDir), array('..', '.'));

function getImages($path) {
    $images = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    usort($images, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    return $images;
}

function filterCategoriesByLetter($categories, $letter) {
    return array_filter($categories, function($category) use ($letter) {
        return stripos($category, $letter) === 0;
    });
}

function filterImages($images, $categoryFilter, $artistFilter, $dateFilter, $searchTerm) {
    return array_filter($images, function($image) use ($categoryFilter, $artistFilter, $dateFilter, $searchTerm) {
        $fileDate = date('Y-m-d', filemtime($image));
        $categoryMatch = $categoryFilter ? strpos($image, $categoryFilter) !== false : true;
        $artistMatch = $artistFilter ? strpos($image, $artistFilter) !== false : true;
        $dateMatch = $dateFilter ? $fileDate == $dateFilter : true;
        $searchMatch = $searchTerm ? strpos($image, $searchTerm) !== false : true;

        return $categoryMatch && $artistMatch && $dateMatch && $searchMatch;
    });
}
function getRandomImageFromArtist($categoryPath) {
    $artists = array_diff(scandir($categoryPath), array('..', '.'));
    $allImages = [];
    foreach ($artists as $artist) {
        $artistPath = $categoryPath . '/' . $artist;
        if (is_dir($artistPath)) {
            $images = getImages($artistPath);
            if (!empty($images)) {
                $allImages[] = $images[array_rand($images)];
            }
        }
    }
    if (!empty($allImages)) {
        return $allImages[array_rand($allImages)];
    }
    return null;
}
function getRandomImageFromArtistFolder($artistPath) {
    if (is_dir($artistPath)) {
        $images = getImages($artistPath);
        if (!empty($images)) {
            return $images[array_rand($images)];
        }
    }
    return null;
}

$selectedCategory = $_GET['category'] ?? null;
$selectedArtist = $_GET['artist'] ?? null;
$filteredImages = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $selectedArtist) {
    $artistPath = $imagesDir . $selectedCategory . '/' . $selectedArtist;
    if (is_dir($artistPath)) {
        $images = getImages($artistPath);
        $filteredImages = filterImages($images, $selectedCategory, $selectedArtist, $_GET['date'] ?? '', $_GET['search'] ?? '');
    }
}

function backLink() {
    if (isset($_GET['artist'])) {
        // Link zur Kategorie, wenn sich der Benutzer in der Artist-Ansicht befindet
        return '?category=' . urlencode($_GET['category']);
    } else if (isset($_GET['category'])) {
        // Link zur Hauptansicht, wenn sich der Benutzer in der Kategorie-Ansicht befindet
        return '?';
    }
    return ''; // Leerer String, wenn sich der Benutzer in der Hauptansicht befindet
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ultimate Gallery</title>
	<link href="./scripts/lightbox.css" rel="stylesheet">
<style>
    body { background-color: #333; color: white; padding-left: 40px; /* Platz für die Schnellsuchleiste */ }
    .grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
        grid-gap: 10px; 
    }
.grid div { 
    display: flex; 
    flex-direction: column; 
    justify-content: flex-end;
    text-align: center; 
    border-radius: 15px; 
    overflow: hidden; 
    position: relative; 
    cursor: pointer; 
}

.grid img { 
    width: 100%; 
    height: 300px; /* oder eine andere feste Höhe */
    object-fit: cover; 
    transition: transform 0.3s ease;
}

.category-name { 
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 0;
    text-align: center;
    font-size: 0.9em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

    .grid div:hover img { 
        transform: scale(1.1); 
    }
    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #555;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        margin-bottom: 20px; /* Zusätzlicher Abstand */
    }
    .back-button:hover {
        background-color: #666;
    }
    .alphabet-filter {
        position: fixed;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        background-color: #444;
        padding: 10px;
        border-radius: 0 10px 10px 0;
        z-index: 100; /* Stellt sicher, dass die Leiste über anderen Elementen liegt */
    }
    .alphabet-filter a {
        color: white;
        text-decoration: none;
        display: block;
        margin: 5px 0;
    }
    .alphabet-filter a:hover {
        color: #aaa;
    }
</style>

</head>
<body>
    <?php if (!$selectedCategory && !$selectedArtist): ?>
        <div class="alphabet-filter">
            <?php foreach (range('A', 'Z') as $letter): ?>
                <a href="#<?php echo $letter; ?>"><?php echo $letter; ?></a>
            <?php endforeach; ?>
        </div>
<?php foreach (range('A', 'Z') as $letter): ?>
    <h2 id="<?php echo $letter; ?>"><?php echo $letter; ?></h2>
    <div class="grid">
        <?php 
        $filteredCategories = filterCategoriesByLetter($categories, $letter);
        foreach ($filteredCategories as $category): 
            $randomImage = getRandomImageFromArtist($imagesDir . $category); 
        ?>
            <div onclick="location.href='?category=<?php echo urlencode($category); ?>'">
                <?php if ($randomImage): ?>
                    <img src="<?php echo $randomImage; ?>" alt="">
                <?php endif; ?>
                <div class="category-name"><?php echo htmlspecialchars($category); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

    <?php else: ?>
        <div>
        <a href="<?php echo backLink(); ?>" class="back-button">Zurück</a>
        </div>
        <form action="" method="get">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
            <input type="hidden" name="artist" value="<?php echo htmlspecialchars($selectedArtist); ?>">
            <input type="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>">
            <input type="text" name="search" placeholder="Suchbegriff" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit">Filtern</button>
        </form>
<div class="grid">
    <?php if (!$selectedArtist): ?>
        <?php
        $categoryPath = $imagesDir . $selectedCategory;
        $artists = array_diff(scandir($categoryPath), array('..', '.'));
        foreach ($artists as $artist): 
            $artistPath = $categoryPath . '/' . $artist;
            $randomImage = getRandomImageFromArtistFolder($artistPath);
            ?>
            <div onclick="location.href='?category=<?php echo urlencode($selectedCategory); ?>&artist=<?php echo urlencode($artist); ?>'">
                <?php if ($randomImage): ?>
                    <img src="<?php echo $randomImage; ?>" alt="<?php echo htmlspecialchars($artist); ?>">
                <?php endif; ?>
                <div class="category-name"><?php echo htmlspecialchars($artist); ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($filteredImages as $image): ?>
            <a href="<?php echo $image; ?>" data-lightbox="gallery"><img src="<?php echo $image; ?>" alt=""></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="./scripts/lightbox.js"></script>
<script>
lightbox.option({
  'resizeDuration': 200,
  'wrapAround': true
});
</script>
</body>
</html>
