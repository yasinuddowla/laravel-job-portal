import './bootstrap';

import { createApp } from 'vue';

// Import components
import JobListing from './components/JobListing.vue';
import JobDetails from './components/JobDetails.vue';
import JobApplicationForm from './components/JobApplicationForm.vue';

// Create Vue app
const app = createApp({});

// Register components globally
app.component('job-listing', JobListing);
app.component('job-details', JobDetails);
app.component('job-application-form', JobApplicationForm);

// Mount the app to the main container
if (document.getElementById('app')) {
    app.mount('#app');
}

// Mount to the job listing container if it exists
if (document.getElementById('job-listing-container')) {
    const jobApp = createApp({});
    jobApp.component('job-listing', JobListing);
    jobApp.mount('#job-listing-container');
}

// Mount to the application form container if it exists
if (document.getElementById('application-form')) {
    const applicationApp = createApp({});
    applicationApp.component('job-application-form', JobApplicationForm);
    applicationApp.mount('#application-form');
}
