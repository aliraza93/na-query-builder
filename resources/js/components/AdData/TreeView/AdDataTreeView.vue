<template>
    <div class="row">
        <!-- Basic Tree -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Basic</h4>
                </div>
                <div class="card-body">
                    <ul id="myUL">
                        <li><span class="caret">Root</span>
                            <ul class="nested">
                                <li @click="show_container = true"><span class="caret">Users</span>
                                    <ul class="nested">
                                        <li>Groups</li>
                                        <li>Containers</li>
                                        <li><span class="caret">Container</span>
                                            <ul class="nested">
                                                <li>OUS</li>
                                                <li>IPS</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Basic Tree -->

        <!-- Tree View Content -->
        <div v-if="show_container == true" class="col-md-9">
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
        <!-- Tree View Content -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            show_container: false,
            status: false,
            errors: null,
        };
    },
    mounted() {
        var toggler = document.getElementsByClassName("caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }
    },
}
</script>
<style scoped>
    /* Remove default bullets */
    ul, #myUL {
    list-style-type: none;
    }

    /* Remove margins and padding from the parent ul */
    #myUL {
    margin: 0;
    padding: 0;
    }

    /* Style the caret/arrow */
    .caret {
    cursor: pointer;
    user-select: none; /* Prevent text selection */
    }

    /* Create the caret/arrow with a unicode, and style it */
    .caret::before {
    content: "\25B6";
    color: black;
    display: inline-block;
    margin-right: 6px;
    }

    /* Rotate the caret/arrow icon when clicked on (using JavaScript) */
    .caret-down::before {
    transform: rotate(90deg);
    }

    /* Hide the nested list */
    .nested {
    display: none;
    }

    /* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
    .active {
    display: block;
    }
</style>