<template>
   <Head title="Dashboard"/>
   
   <DefaultLayout>
      <div class="row">
         <!-- Page Header -->
         <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h4 class="mb-1">Analytics Dashboard</h4>
                  <p class="text-muted mb-0">Last 30 days overview</p>
               </div>
               <div>
                  <button class="btn btn-outline-primary btn-sm" @click="refreshData">
                     <i class="bx bx-refresh me-2"></i>Refresh Data
                  </button>
               </div>
            </div>
         </div>
         
         <!-- Summary Cards -->
         <div class="col-xl-3 col-md-6 mb-4">
            <div class="mb-4">
               <div class="card card-border-shadow-primary">
                  <div class="card-body">
                     <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary">
                           <i class="icon-base bx bx-user icon-lg fs-4"></i>
                        </span>
                        </div>
                        <h3 class="card-title mb-0">{{ totalVisitors }}</h3>
                     </div>
                     <p class="mb-2">Total Visitors</p>
                     <p class="mb-0">
                     <span class="fw-medium me-1" :class="visitorsTrend >= 0 ? 'text-success' : 'text-danger'">
                        <i :class="visitorsTrend >= 0 ? 'bx bx-trending-up' : 'bx bx-trending-down'"></i>
                        {{ Math.abs(visitorsTrend) }}%
                     </span>
                        <span class="text-muted">from last period</span>
                     </p>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-xl-3 col-md-6">
            <div class="mb-4">
               <div class="card card-border-shadow-success">
                  <div class="card-body">
                     <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success">
                           <i class="icon-base bx bx-show icon-lg fs-4"></i>
                        </span>
                        </div>
                        <h3 class="card-title mb-0">{{ totalPageViews }}</h3>
                     </div>
                     <p class="mb-2">Page Views</p>
                     <p class="mb-0">
                     <span class="fw-medium me-1" :class="pageViewsTrend >= 0 ? 'text-success' : 'text-danger'">
                        <i :class="pageViewsTrend >= 0 ? 'bx bx-trending-up' : 'bx bx-trending-down'"></i>
                        {{ Math.abs(pageViewsTrend) }}%
                     </span>
                        <span class="text-muted">from last period</span>
                     </p>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-xl-3 col-md-6">
            <div class="mb-4">
               <div class="card card-border-shadow-info">
                  <div class="card-body">
                     <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info">
                           <i class="icon-base bx bx-file icon-lg fs-4"></i>
                        </span>
                        </div>
                        <h3 class="card-title mb-0">{{ averagePagesPerVisit }}</h3>
                     </div>
                     <p class="mb-2">Avg. Page Visit</p>
                     <p class="mb-0">
                        <span class="text-muted">Per session</span>
                     </p>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-xl-3 col-md-6">
            <div class="mb-4">
               <div class="card card-border-shadow-warning">
                  <div class="card-body">
                     <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning">
                           <i class="icon-base bx bx-globe icon-lg fs-4"></i>
                        </span>
                        </div>
                        <h3 class="card-title mb-0">{{ topCountry }}</h3>
                     </div>
                     <p class="mb-2">Top Country</p>
                     <p class="mb-0">
                     <span class="fw-medium me-1 text-success">
                        {{ topCountryViews }} views
                     </span>
                        <span class="text-muted">from last period</span>
                     </p>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Visitors & Page Views Chart -->
         <div class="col-xl-8 col-lg-7">
            <div class="mb-4">
               <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center border-bottom-0">
                     <div class="card-title mb-0">
                        <h5 class="m-0 me-2">
                           Visitors & Page Views Trend
                        </h5>
                     </div>
                     <div class="dropdown">
                        <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                           <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-muted fs-4"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                           <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                           <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                           <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                        </ul>
                     </div>
                  </div>
                  <div class="card-body">
                     <apexchart
                        type="area"
                        height="350"
                        :options="visitorsChartOptions"
                        :series="visitorsChartSeries"
                     ></apexchart>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Top Countries Chart -->
         <div class="col-xl-4 col-lg-5">
            <div class="mb-4">
               <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between border-bottom-0">
                     <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Top Countries</h5>
                     </div>
                  </div>
                  <div class="card-body">
                     <apexchart
                        type="donut"
                        height="350"
                        :options="countriesChartOptionsComputed"
                        :series="countriesSeries"
                     ></apexchart>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Browser & Device Stats -->
         <div class="col-xl-6 col-lg-6 col-12">
            <div class="mb-4">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-title mb-0">Traffic Sources</h5>
                  </div>
                  <div class="card-body">
                     <apexchart
                        v-if="trafficSourcesChartSeries[0].data.length > 0"
                        type="bar"
                        height="350"
                        :options="trafficSourcesChartOptions"
                        :series="trafficSourcesChartSeries"
                     ></apexchart>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Top Pages Table -->
         <div class="col-12">
            <div class="mb-4">
               <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between border-bottom-0">
                     <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Most Visited Pages</h5>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="table-responsive text-wrap">
                        <table class="table table-hover table-bordered">
                           <thead>
                           <tr>
                              <th>Page URL</th>
                              <th class="text-end">Page Views</th>
                              <th class="text-end">% of Total</th>
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="(page, index) in topPagesData" :key="index">
                              <td class="text-wrap">
                                 <div class="d-flex align-items-center">
                                    <div class="rank-badge me-3">{{ index + 1 }}</div>
                                    <div>
                                       <div class="fw-medium">{{ page.url }}</div>
                                       <small class="text-muted">{{ page.title }}</small>
                                    </div>
                                 </div>
                              </td>
                              <td class="text-end">
                                 <span class="badge bg-light text-dark">{{ page.pageViews }}</span>
                              </td>
                              <td class="text-end">
                                 <div class="progress" style="height: 6px; width: 60px; float: right;">
                                    <div class="progress-bar bg-primary"
                                         :style="`width: ${page.percentage}%`"></div>
                                 </div>
                                 <small class="text-muted d-block mt-1">{{ page.percentage }}%</small>
                              </td>
                           </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Recent Activity Timeline -->
         <!--         <div class="col-12">-->
         <!--            <div class="card">-->
         <!--               <div class="card-header">-->
         <!--                  <h5 class="card-title mb-0">Quick Stats</h5>-->
         <!--               </div>-->
         <!--               <div class="card-body">-->
         <!--                  <div class="row text-center">-->
         <!--                     <div class="col-md-3 col-6 mb-3">-->
         <!--                        <div class="quick-stat">-->
         <!--                           <i class="bx bx-time-five fs-2 text-primary mb-2"></i>-->
         <!--                           <h4>2:45</h4>-->
         <!--                           <p class="text-muted mb-0">Avg. Session Duration</p>-->
         <!--                        </div>-->
         <!--                     </div>-->
         <!--                     <div class="col-md-3 col-6 mb-3">-->
         <!--                        <div class="quick-stat">-->
         <!--                           <i class="bx bx-trending-up fs-2 text-success mb-2"></i>-->
         <!--                           <h4>68%</h4>-->
         <!--                           <p class="text-muted mb-0">Bounce Rate</p>-->
         <!--                        </div>-->
         <!--                     </div>-->
         <!--                     <div class="col-md-3 col-6 mb-3">-->
         <!--                        <div class="quick-stat">-->
         <!--                           <i class="bx bx-user-plus fs-2 text-info mb-2"></i>-->
         <!--                           <h4>1,234</h4>-->
         <!--                           <p class="text-muted mb-0">New Users</p>-->
         <!--                        </div>-->
         <!--                     </div>-->
         <!--                     <div class="col-md-3 col-6 mb-3">-->
         <!--                        <div class="quick-stat">-->
         <!--                           <i class="bx bx-refresh fs-2 text-warning mb-2"></i>-->
         <!--                           <h4>892</h4>-->
         <!--                           <p class="text-muted mb-0">Returning Users</p>-->
         <!--                        </div>-->
         <!--                     </div>-->
         <!--                  </div>-->
         <!--               </div>-->
         <!--            </div>-->
         <!--         </div>-->
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import { Head } from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';

export default {
   components: {
      DefaultLayout,
      Head,
      apexchart: VueApexCharts,
   },
   props: {
      visitorsAndPageViews: {
         type: Array,
         required: true
      },
      topPages: {
         type: Array,
         required: true
      },
      topCountries: {
         type: Object,
         required: true
      },
      trafficSources: {
         type: Array,
         required: true
      },
   },
   data() {
      return {
         visitorsChartOptions: {
            chart: {
               type: 'area',
               height: 350,
               toolbar: {
                  show: true,
                  tools: {
                     download: true,
                     selection: false,
                     zoom: true,
                     zoomin: true,
                     zoomout: true,
                     pan: false,
                  }
               },
               animations: {
                  enabled: true,
                  easing: 'easeinout',
                  speed: 800,
               }
            },
            colors: ['#4CAF50', '#2196F3'],
            dataLabels: {
               enabled: false
            },
            stroke: {
               curve: 'smooth',
               width: 2
            },
            fill: {
               type: 'gradient',
               gradient: {
                  opacityFrom: 0.6,
                  opacityTo: 0.1,
               }
            },
            xaxis: {
            },
            yaxis: {
               categories: [],
               title: {
                  text: 'Count'
               }
            },
            legend: {
               position: 'bottom',
               horizontalAlign: 'center'
            },
            tooltip: {
               x: {
                  format: 'dd MMM yyyy'
               },
               y: {
                  formatter: function(val) {
                     return val.toLocaleString()
                  }
               }
            },
            grid: {
               borderColor: '#f1f1f1',
            }
         },
         visitorsChartSeries: [],
         countriesChartOptions: {
            chart: {
               type: 'donut',
            },
            colors: ['#4CAF50', '#2196F3', '#FFC107', '#FF5722', '#9C27B0'],
            labels: this.countriesLabels,
            legend: {
               position: 'bottom'
            },
            plotOptions: {
               pie: {
                  donut: {
                     size: '65%',
                     labels: {
                        show: true,
                        name: {
                           show: true,
                           fontSize: '16px',
                           fontWeight: 600,
                        },
                        value: {
                           show: true,
                           fontSize: '24px',
                           fontWeight: 700,
                           formatter: function(val) {
                              return val.toLocaleString()
                           }
                        },
                        total: {
                           show: true,
                           label: 'Total Views',
                           fontSize: '14px',
                           fontWeight: 600,
                           formatter: function (w) {
                              return w.globals.seriesTotals.reduce((a, b) => {
                                 return a + b
                              }, 0).toLocaleString()
                           }
                        }
                     }
                  }
               }
            },
            dataLabels: {
               enabled: true,
               formatter: function(val) {
                  return val.toFixed(1) + '%'
               }
            }
         },
         countriesChartSeries: this.countriesSeries,
         trafficSourcesChartOptions: {
            chart: {
               type: 'bar',
               height: 350,
               toolbar: {
                  show: false
               }
            },
            plotOptions: {
               bar: {
                  horizontal: true,
                  borderRadius: 4,
                  dataLabels: {
                     position: 'top',
                  },
               }
            },
            colors: ['#4CAF50'],
            dataLabels: {
               enabled: true,
               offsetX: 30,
               style: {
                  fontSize: '12px',
                  colors: ['#333']
               }
            },
            xaxis: {
               categories: [],
            },
            grid: {
               borderColor: '#f1f1f1',
            }
         },
         trafficSourcesChartSeries: [{
            name: 'Visitors',
            data: []
         }],
      }
   },
   computed: {
      totalVisitors() {
         return this.visitorsAndPageViews.reduce((sum, item) => sum + item.visitors, 0).toLocaleString();
      },
      totalPageViews() {
         return this.visitorsAndPageViews.reduce((sum, item) => sum + item.pageViews, 0).toLocaleString();
      },
      averagePagesPerVisit() {
         const totalVisitors = this.visitorsAndPageViews.reduce((sum, item) => sum + item.visitors, 0);
         const totalPageViews = this.visitorsAndPageViews.reduce((sum, item) => sum + item.pageViews, 0);
         return totalVisitors > 0 ? (totalPageViews / totalVisitors).toFixed(2) : '0.00';
      },
      visitorsTrend() {
         // Calculate trend (simplified - compare first half vs second half)
         const midPoint = Math.floor(this.visitorsAndPageViews.length / 2);
         const firstHalf = this.visitorsAndPageViews.slice(0, midPoint).reduce((sum, item) => sum + item.visitors, 0);
         const secondHalf = this.visitorsAndPageViews.slice(midPoint).reduce((sum, item) => sum + item.visitors, 0);
         return firstHalf > 0 ? (((secondHalf - firstHalf) / firstHalf) * 100).toFixed(1) : 0;
      },
      pageViewsTrend() {
         const midPoint = Math.floor(this.visitorsAndPageViews.length / 2);
         const firstHalf = this.visitorsAndPageViews.slice(0, midPoint).reduce((sum, item) => sum + item.pageViews, 0);
         const secondHalf = this.visitorsAndPageViews.slice(midPoint).reduce((sum, item) => sum + item.pageViews, 0);
         return firstHalf > 0 ? (((secondHalf - firstHalf) / firstHalf) * 100).toFixed(1) : 0;
      },
      topCountry() {
         if (this.topCountries && this.topCountries.length > 0) {
            return this.topCountries[0].country;
         }
         return 'N/A';
      },
      topCountryViews() {
         if (this.topCountries && this.topCountries.length > 0) {
            return parseInt(this.topCountries[0].screenPageViews).toLocaleString();
         }
         return '0';
      },
      topPagesData() {
         const totalViews = this.topPages.reduce((sum, page) => sum + parseInt(page.pageViews), 0);
         return this.topPages.map(page => ({
            url: page.url,
            title: page.pageTitle || page.url,
            pageViews: parseInt(page.pageViews).toLocaleString(),
            percentage: ((parseInt(page.pageViews) / totalViews) * 100).toFixed(1)
         }));
      },
      countriesLabels() {
         return this.topCountries.map(c => c.country);
      },
      countriesSeries() {
         return this.topCountries.map(c => c.screenPageViews);
      },
      countriesChartOptionsComputed() {
         return {
            chart: { type: 'donut' },
            labels: this.countriesLabels,
            legend: { position: 'bottom' }
         }
      },
   },
   watch: {
      countriesLabels(newVal) {
         this.countriesChartOptions = {
            ...this.countriesChartOptions,
            labels: newVal
         }
      },
      countriesSeries(newVal) {
         this.countriesSeries = newVal
      }
   },
   mounted() {
      this.initializeCharts();
   },
   methods: {
      initializeCharts() {
         // console.log('Initializing charts with data:', {
         //    visitorsAndPageViews: this.visitorsAndPageViews,
         //    topCountries: this.topCountries,
         //    trafficSources: this.trafficSources
         // });
         
         if (this.visitorsAndPageViews && this.visitorsAndPageViews.length > 0) {
            const dates = this.visitorsAndPageViews.map(item => item.date);
            const visitors = this.visitorsAndPageViews.map(item => parseInt(item.visitors) || 0);
            const pageViews = this.visitorsAndPageViews.map(item => parseInt(item.pageViews) || 0);
            
            this.visitorsChartOptions = {
               ...this.visitorsChartOptions,
               xaxis: {
                  ...this.visitorsChartOptions.xaxis,
                  categories: dates,
                  labels: {
                     format: 'dd MMM'
                  },
               }
            };
            
            this.visitorsChartSeries = [
               {
                  name: 'Visitors',
                  data: visitors
               },
               {
                  name: 'Page Views',
                  data: pageViews
               }
            ];
         }
         
         if (this.topCountries && this.topCountries.length > 0) {
            this.countriesChartOptions = {
               ...this.countriesChartOptions,
               labels: this.topCountries.map(c => c.country)
            };
            this.countriesSeries = this.topCountries.map(c => parseInt(c.screenPageViews) || 0);
         }
         
         if (this.trafficSources && this.trafficSources.length > 0) {
            this.trafficSourcesChartOptions = {
               ...this.trafficSourcesChartOptions,
               xaxis: {
                  categories: this.trafficSources.map(s => s.source)
               }
            };
            this.trafficSourcesChartSeries = [{
               name: 'Visitors',
               data: this.trafficSources.map(s => parseInt(s.users) || 0)
            }];
         }
      },
      refreshData() {
         this.$inertia.get(route('admin.analytics.refresh'), { preserveScroll: true });
      }
   }
}
</script>

<style scoped>
.stats-card {
   border: none;
   box-shadow: 0 2px 8px rgba(0,0,0,0.08);
   transition: transform 0.3s, box-shadow 0.3s;
}
.stats-card:hover {
   transform: translateY(-5px);
   box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}
.stats-icon {
   width: 48px;
   height: 48px;
   border-radius: 12px;
   display: flex;
   align-items: center;
   justify-content: center;
   color: white;
}
.rank-badge {
   width: 32px;
   height: 32px;
   border-radius: 50%;
   background: #f0f0f0;
   display: flex;
   align-items: center;
   justify-content: center;
   font-weight: 600;
   font-size: 14px;
   color: #333;
}
.quick-stat {
   padding: 1rem;
   border-radius: 8px;
   transition: all 0.3s;
}
.quick-stat:hover {
   background: #f8f9fa;
}
</style>
