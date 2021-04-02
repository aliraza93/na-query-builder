<template>
	
	<div class="row d-flex justify-content-between mx-0">
		<!-- <div class="col-sm-12 col-md-8">
			<div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 3 of 100 entries</div>
		</div> -->
		<div class="col-sm-12 col-md-12" v-if="pageData.last_page > 1">
			<nav aria-label="Page navigation example">
				<ul class="pagination justify-content-center">
					<li class="page-item">
					<a class="page-link" href="#" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
						<span class="sr-only">Previous</span>
					</a>
					</li>
					
					<li
						v-for="pageNo in range(paginateLoop, numberOfPage)" :key="pageNo"
						class="page-item"
						:class="[ ((pageData.current_page == pageNo) ? 'active' : '') ]"
						>
						<a class="page-link" :href="'?page='+pageNo" @click.prevent="pageClicked(pageNo)">{{ pageNo }}</a>
					</li>
					
					<li class="page-item">
					<a class="page-link" href="#" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
						<span class="sr-only">Next</span>
					</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>

</template>

<script type="text/javascript">

	export default{

		props : ['pageData'],

		data(){

			return {


			}
		},

		methods : {
       
			range(start, count) {
				return Array.apply(0, Array(count)).map(function(element, index) {
					return index + start;
				});
			},

			pageClicked(page){
                
                this.$parent.pageClicked(page);

			}
		},

			computed: {
			paginateLoop() {
				let pageData = this.pageData;
				if (pageData.last_page > 11) {
					if (pageData.last_page - 5 <= pageData.current_page) {
						return pageData.last_page - 10;
					}
					if (pageData.current_page > 6) {
						return pageData.current_page - 5;
					}
				}
				return 1;
			},
			numberOfPage() {
				if (this.pageData.last_page < 11) {
					return this.pageData.last_page;
				} else {
					return 11;
				}
			}
		}




	}


</script>