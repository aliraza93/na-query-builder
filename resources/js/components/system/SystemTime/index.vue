<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">System Time</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select id="timezone" class="form-control">
                                        <option value="Timezone" selected>Timezone</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option value="SystemV" selected>SystemV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <datepicker style="width: 100%;" :disabled-dates="state.disabledDates" placeholder="Please Select A Date" :bootstrap-styling="true"></datepicker>
                                </div>
                                <div class="form-group">
                                    <vue-timepicker input-class="form-control" input-width="100px" format="hh:mm A" close-on-complete></vue-timepicker>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1" />
                                        <label class="custom-control-label" for="customSwitch1">NTP</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn">Add NTP</button>
                                </div>
                                <button type="button" class="btn btn-primary" style="float: right;">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
            <div class="col-md-2"></div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue'

export default {
    
    components: {
        Datepicker,
        VueTimepicker
    },

    data() {
        return {
            state: {
                disabledDates: {
                    to: new Date(), // Disable all dates up to specific date
                    days: [],
                    dates: [
                        
                    ], 
                    // Disable Saturday's and Sunday's
                    // a custom function that returns true if the date is disabled
                    // this can be used for wiring you own logic to disable a date if none
                    // of the above conditions serve your purpose
                    // this function should accept a date and return true if is disabled
                    customPredictor: function() {
                        // disables the date if it is a multiple of 5
                        //if(this.day[i]Time.mon_start_time == '' || this.dayTime.mon_end_time == ''){
                        //    return true
                        //}
                    }
                }
            },
        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {
            
    },

    computed: {
        
    }
}
</script>

<style scoped>
    .form-group {
        margin-right: 10px;
    }
</style>