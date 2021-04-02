<template>
    <div class="tab-pane" id="groups" aria-labelledby="groups-tab" role="tabpanel">
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
                    <button style="float: right;" type="button" class="btn btn-primary">Add Group</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Distinguished Name</th>
                            <th class="text-center">Group Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Distinguished Name</td>
                            <td class="text-center">Group 1</td>
                        </tr>
                        <tr>
                            <td class="text-center">Distinguished Name</td>
                            <td class="text-center">Group 1</td>
                        </tr>
                        <tr>
                            <td class="text-center">Distinguished Name</td>
                            <td class="text-center">Group 1</td>
                        </tr>
                        <tr>
                            <td class="text-center">Distinguished Name</td>
                            <td class="text-center">Group 1</td>
                        </tr>
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