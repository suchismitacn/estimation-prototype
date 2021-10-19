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
                Estimation
            </div>
            <div class="card-body">
                <div class="alert alert-danger d-none">
                </div>
                <form class="row" method="POST" action="{{ route('get.quote') }}" id="estimator-form">
                    {{ csrf_field() }}
                    <div class="col-4 mb-3">
                        <label for="vehicleRegNo" class="form-label">Vehicle Registration</label>
                        <input type="text" class="form-control" id="vehicleRegNo" name="vehicle_reg_no"
                            value="">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="vehicleEstimatedValue" class="form-label">Vehicle Estimated Value</label>
                        <input type="number" class="form-control" id="vehicleEstimatedValue"
                            name="vehicle_estimated_value" value="" min="1"
                            max="25000">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="dob" class="form-label">DOB</label>
                        <input type="date" class="form-control" id="dob" name="date_of_birth"
                            value="">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" class="form-control" id="postcode_1" name="postcode"
                            value="">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="voluntaryExcess" class="form-label">Voluntary Excess</label>
                        <input type="number" class="form-control" id="voluntaryExcess" step="50" name="voluntary_excess"
                            value="" min="0" max="1000">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="jobTitle" class="form-label">Job Title</label>
                        <input class="form-control" list="jobTitles" id="jobTitle" placeholder="Type to search..."
                            name="job_title" value="">
                        <datalist id="jobTitles">
                            @foreach ($jobs as $job)
                                <option value="{{ $job }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-4 mb-3">
                        <label for="noClaimBonus" class="form-label">No Claim Bonus</label>
                        <input type="number" class="form-control" id="noClaimBonus" name="no_claim_bonus"
                            value="" min="0" max="20">
                    </div>
                    <button type="submit" class="btn btn-primary">Get a quote</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header text-center">
                        Data Sent
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span id="vehicle_reg_no">Vehicle Registration: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="vehicle_estimated_value">Vehicle Estimated Value: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="date_of_birth">DOB: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="postcode">Postcode: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="voluntary_excess">Voluntary Excess: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="job_title">Job Title: <strong></strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="no_claim_bonus">No Claim Bonus: <strong></strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header text-center">
                        Estimates
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span id="prediction_min">Cheapest Price: <strong>&pound;</strong></span>
                            </li>
                            <li class="list-group-item">
                                <span id="prediction_top5">Average Price: <strong>&pound;</strong> </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        let estimator = $('#estimator-form');

        $(function () {
            formSubmit(estimator);
        });

        function formSubmit(form) {
            form.on('submit', function(event){
                event.preventDefault();
                var btn = form.find('button[type="submit"]');
                var alert = $('.alert');

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    beforeSend: function() {
                        btn.attr('disabled', true);
                        alert.addClass('d-none').removeClass('alert-danger').removeClass('d-block');
                        alert.html('');
                    },
                    success: function(response) {
                        console.log(response);
                        btn.attr('disabled', false);
                        alert.addClass('d-block').addClass('alert-success').removeClass('d-none');
                        alert.append('<p> Success </p>');

                        if (typeof response.data.inputs !== 'undefined') {
                            var inputs = response.data.inputs;
                            if (inputs.length !== 0) {
                                for (const [key, value] of Object.entries(inputs)) {
                                    $('#' + key).find('strong').text(value);
                                }
                            }
                        }

                        if (typeof response.data.estimate !== 'undefined') {
                            var estimate = response.data.estimate;
                            if (estimate.length !== 0) {
                                for (const [key, value] of Object.entries(estimate)) {
                                    $('#' + key).find('strong').html('&pound' + value);
                                }
                            }
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        btn.attr('disabled', false);
                        alert.addClass('d-block').addClass('alert-danger').removeClass('d-none');
                        if (typeof response.responseJSON.data !== 'undefined') {
                            var errors = response.responseJSON.data;
                            if (errors.length !== 0) {
                                for (var i = 0; i < errors.length; i++) {
                                    alert.append('<p>' + errors[i] + '</p>');
                                }
                            }
                        } else {
                            alert.append('<p>' + response.responseJSON.message + '</p>');
                        }
                    }
                });
            });
        }
    </script>
</body>

</html>
