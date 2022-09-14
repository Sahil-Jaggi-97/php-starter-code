
<body>
    <div class="container">
        <h2>HTML Forms</h2>
        <?php print_r($Data)?>
        <form id='studentForm' method="post" enctype="multipart/form-data".>
            <label for="name">Name:</label><br>
            <input type="text" id="fname" name="fname" value="John"><br>
            
            <label for="lname">Last name:</label><br>
            <input type="text" id="lname" name="lname" value="Doe"><br><br>
            
            <input type='file' name='file[]' id='file' multiple required>

            <input type="submit" id="btn" value="Submit">
        </form>
    </div> 
</body>
<script src="assets/js/app.js"></script>


