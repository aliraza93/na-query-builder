{{-- Include Page Content --}}
<section>
    <div class="container">
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rule Builder</h4>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <div id="builder"></div>
                        </div>
                    </div>
                    <br><br>
                        <form>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="per_page">Per Page</label>
                                    <select class="form-control" name="per_page" id="per_page">
                                        <option value="10">10</option>
                                        <option value="20" selected>20</option>
                                        <option value="30">30</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="from">From</label>
                                    <input type="date" class="form-control" name="" id="from">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="to">To</label>
                                    <input type="date" class="form-control" name="" id="to">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="report_name">Report Name</label>
                                        <input type="text" class="form-control" placeholder="Report Name" name="" id="report_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <button style="float: right; margin-left: 5px;" type="submit" class="btn btn-primary">Save</button>
                                    <button style="float: right; margin-left: 5px;" type="button" class="btn btn-primary">Reset</button>
                                    <button style="float: right;" type="button" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
        </div>
    </div>
</section>