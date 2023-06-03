<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobilVendor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">    
    <script src="./assets/knockout-3.5.1.js"></script>     
    <script src="./assets/axios.min.js"></script>     
</head>
<body>
    <nav class="navbar navbar-expand-lg nav-light bg-light mb-4">
        <div class="container">
            <a href="/" class="navbar-brand h1">MobilVendor</a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/"></a>
                </li>                      
            </ul>
        </div>      
    </nav>
    <div class="container">
        <div class="row">
			<?= $content ?>
		</div>
	</div>   
    
</body>
</html>