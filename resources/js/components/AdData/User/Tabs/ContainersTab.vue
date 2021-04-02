<template>
    <div class="tab-pane active" id="containers" aria-labelledby="containers-tab" role="tabpanel">
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
                    <button style="float: right;" type="button" class="btn btn-primary">Add Container</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Container Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">Container 1</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">Container 1</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">Container 1</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">Container 1</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
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