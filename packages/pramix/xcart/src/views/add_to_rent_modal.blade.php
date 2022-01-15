<form action="" class="rent_form">
    <div class="form-group">

        <input type="number" placeholder="Duration" name="duration_rent" id="duration_rent" class="name form-control" required />
        </div>

    <div class="form-check">
        <label class="form-check-label">
            <input type="radio" class="form-check-input" name="rent_duration_type" value="D" checked>Days
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
            <input type="radio" class="form-check-input" name="rent_duration_type" value="W">Weeks
        </label>
    </div>
    <div class="form-check disabled">
        <label class="form-check-label">
            <input type="radio" class="form-check-input" name="rent_duration_type" value="M">Months
        </label>
    </div>

    </form>