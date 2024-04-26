<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>

<h1>Welcome to the Application</h1>

<!-- Navigation Links -->
<ul>
    <li><a href="profile">Create Profile</a></li>
    <li><a href="publications">My publications</a></li>
    <li><a href="logout">Logout</a></li>
</ul>

<!-- Publication List -->
<h2>Publications</h2>
<ul>
    <?php
    // Get publications from database
    $publications = getPublications();

    // Display each publication as a list item with hyperlink
    foreach ($publications as $publication) {
        echo "<li><a href='publication.php?id={$publication['id']}'>{$publication['title']}</a></li>";
    }
    ?>
</ul>

</body>
</html>