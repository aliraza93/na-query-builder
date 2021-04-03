<template>
    <div class="tab-pane" id="policy" aria-labelledby="policy-tab" role="tabpanel">
        <div class="card mt-2">
            <div class="d-flex justify-content-between align-items-center mx-0 row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_filter">
                        <label style="float: left;">Search:
                            <input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <button style="float: right;" type="button" class="btn btn-primary">Add Policy</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Inherit Status</th>
                            <th class="text-center">Object Type</th>
                            <th class="text-center">Object Name</th>
                            <th class="text-center">Priority</th>
                            <th class="text-center">Policy Name</th>
                            <th class="text-center">System Policy</th>
                            <th class="text-center">Enforced</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
}
</script>