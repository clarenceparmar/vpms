<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>

    <?php include( $_SERVER[ 'DOCUMENT_ROOT' ] . "/assets/admin_header.html"  ); ?>
    <main class="main">
        <header>
            <h1>Admin Panel</h1>
        </header>

        
        <section>
        
            <!-- Main Content -->
            <main>
            
                <div class="dashboard-stats">
                    <div class="card">
                        <h3>Today's Vehicle Entries</h3>
                        <p>0</p>
                    </div>
                    <div class="card">
                        <h3>Yesterday's Vehicle Entries</h3>
                        <p>0</p>
                    </div>
                    <div class="card">
                        <h3>Last 7 Days Vehicle Entries</h3>
                        <p>0</p>
                    </div>
                    <div class="card">
                        <h3>Total Vehicle Entries</h3>
                        <p>2</p>
                    </div>
                    <div class="card">
                        <h3>Total Registered Users</h3>
                        <p>2</p>
                    </div>
                    <div class="card">
                        <h3>Listed Categories</h3>
                        <p>4</p>
                    </div>
                </div>
            </main>
        </section>
    </main>
</body>
</html>
