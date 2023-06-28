<footer class="fixed-bottom bg-dark text-white py-3" style="padding-top: 90px">
    <div class="container-fluid">
        <div class="row">
            <div class="col text-center">
                <p>© 2023 Ruslan Liakhovets. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</body>
</html>

<?php if (isset($conn))
    $conn->close(); ?>