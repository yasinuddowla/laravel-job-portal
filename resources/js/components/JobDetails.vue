<template>
    <div class="job-details">
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else class="card mb-4">
            <div class="card-body">
                <h1 class="card-title h3 mb-2">{{ job.title }}</h1>
                <h2 class="card-subtitle h6 text-muted mb-4">
                    {{ job.company.name }}
                </h2>

                <div class="mb-4 d-flex flex-wrap">
                    <div v-if="job.location" class="me-4 mb-2">
                        <i
                            class="fas fa-map-marker-alt me-1 text-secondary"
                        ></i>
                        <span>{{ job.location }}</span>
                    </div>
                    <div v-if="job.type" class="me-4 mb-2">
                        <i class="fas fa-briefcase me-1 text-secondary"></i>
                        <span>{{ job.type }}</span>
                    </div>
                    <div v-if="job.salary_range" class="me-4 mb-2">
                        <i
                            class="fas fa-money-bill-wave me-1 text-secondary"
                        ></i>
                        <span>{{ job.salary_range }}</span>
                    </div>
                    <div class="me-4 mb-2">
                        <i class="fas fa-clock me-1 text-secondary"></i>
                        <span>Posted {{ formatDate(job.created_at) }}</span>
                    </div>
                </div>

                <h3 class="h5 mb-3">Job Description</h3>
                <div class="job-description mb-4">
                    {{ job.description }}
                </div>

                <div class="d-flex align-items-center small text-muted">
                    <div class="me-3">
                        <i class="fas fa-eye me-1"></i>
                        {{ job.view_count || 0 }} views
                    </div>
                    <div>
                        <i class="fas fa-paper-plane me-1"></i>
                        {{ job.applications_count || 0 }} applications
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="!loading && job.company && job.company.description"
            class="card mb-4"
        >
            <div class="card-header">
                <h3 class="h5 mb-0">About {{ job.company.name }}</h3>
            </div>
            <div class="card-body">
                <p>{{ job.company.description }}</p>
                <a
                    v-if="job.company.website"
                    :href="job.company.website"
                    target="_blank"
                    class="btn btn-outline-primary btn-sm"
                >
                    <i class="fas fa-external-link-alt me-1"></i> Visit Website
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        jobId: {
            type: [Number, String],
            required: true,
        },
    },
    data() {
        return {
            job: {
                company: {},
            },
            loading: true,
            error: null,
        };
    },
    mounted() {
        this.fetchJobDetails();
    },
    methods: {
        fetchJobDetails() {
            this.loading = true;
            fetch(`/api/jobs/${this.jobId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Failed to load job details");
                    }
                    return response.json();
                })
                .then((data) => {
                    this.job = data;
                    this.loading = false;
                })
                .catch((error) => {
                    console.error("Error fetching job details:", error);
                    this.error =
                        "Failed to load job details. Please try again later.";
                    this.loading = false;
                });
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < 1) {
                return "Today";
            } else if (diffDays === 1) {
                return "Yesterday";
            } else if (diffDays < 7) {
                return `${diffDays} days ago`;
            } else if (diffDays < 30) {
                return `${Math.floor(diffDays / 7)} week(s) ago`;
            } else {
                return `${Math.floor(diffDays / 30)} month(s) ago`;
            }
        },
    },
};
</script>

<style scoped>
.job-details {
    margin-bottom: 2rem;
}
</style>
