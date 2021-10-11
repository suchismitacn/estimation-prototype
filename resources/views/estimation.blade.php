<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Estimation</title>
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                Estimation Prototype
            </div>
            <div class="card-body">
                <form class="row" method="POST" action="{{ route('get.quote') }}">
                    {{ csrf_field() }}
                    <div class="col-4 mb-3">
                        <label for="vehicleRegNo" class="form-label">Vehicle Registration</label>
                        <input type="text" class="form-control" id="vehicleRegNo" name="vehicle_reg_no">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="vehicleEstimatedValue" class="form-label">Vehicle Estimated Value</label>
                        <input type="number" class="form-control" id="vehicleEstimatedValue"
                            name="vehicle_estimated_value">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="dob" class="form-label">DOB</label>
                        <input type="date" class="form-control" id="dob" name="date_of_birth">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" class="form-control" id="postcode" name="postcode">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="voluntaryExcess" class="form-label">Voluntary Excess</label>
                        <input type="number" class="form-control" id="voluntaryExcess" step="50"
                            name="voluntary_excess">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="jobTitle" class="form-label">Job Title</label>
                        <input class="form-control" list="datalistOptions" id="jobTitle" placeholder="Type to search..."
                            name="job_title">
                        <datalist id="datalistOptions">
                            @foreach ($jobs as $job)
                                <option value="{{ $job }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="noClaimBonus" class="form-label">No Claim Bonus</label>
                        <input type="number" class="form-control" id="noClaimBonus" name="no_claim_bonus">
                    </div>
                    <button type="submit" class="btn btn-primary">Get a quote</button>
                </form>
            </div>
        </div>
        @isset($estimates)
        <div class="card">
            <div class="card-header text-center">
                Estimates
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">An item</li>
                <li class="list-group-item">A second item</li>
                <li class="list-group-item">A third item</li>
            </ul>
        </div>
        @endisset
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>