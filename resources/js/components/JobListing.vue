<template>
    <div class="job-listing">
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else-if="jobs.length === 0" class="alert alert-info">
            No jobs available at the moment. Please check back later.
        </div>

        <div v-else>
            <div class="card mb-4" v-for="job in jobs" :key="job.id">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h2 class="card-title h5">
                                <a
                                    :href="'/jobs/' + job.id"
                                    class="text-decoration-none"
                                    >{{ job.title }}</a
                                >
                            </h2>
                            <h3 class="card-subtitle h6 text-muted mb-2">
                                {{ job.company.name }}
                            </h3>
                            <p class="card-text text-truncate mb-1">
                                {{ job.description }}
                            </p>
                            <div class="small text-muted mb-2">
                                <span v-if="job.location" class="me-3">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ job.location }}
                                </span>
                                <span v-if="job.type" class="me-3">
                                    <i class="fas fa-briefcase me-1"></i>
                                    {{ job.type }}
                                </span>
                                <span v-if="job.salary_range">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ job.salary_range }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <div class="mb-2 small">
                                <i class="fas fa-eye me-1"></i>
                                {{ job.view_count || 0 }} views
                            </div>
                            <div class="mb-2 small">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ job.applications_count || 0 }} applications
                            </div>
                            <a
                                :href="'/jobs/' + job.id"
                                class="btn btn-primary btn-sm"
                                >View Details</a
                            >
                        </div>
                    </div>
                </div>
                <div class="card-footer small text-muted">
                    Posted {{ formatDate(job.created_at) }}
                </div>
            </div>

            <div
                class="pagination-links mt-4 d-flex justify-content-center"
                v-if="links.length > 3"
            >
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li
                            v-for="(link, index) in links"
                            :key="index"
                            class="page-item"
                            :class="{
                                active: link.active,
                                disabled: !link.url,
                            }"
                        >
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="goToPage(link)"
                                v-html="link.label"
                            ></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            jobs: [],
            links: [],
            meta: {},
            loading: true,
            currentPage: 1,
        };
    },
    mounted() {
        this.fetchJobs();
    },
    methods: {
        fetchJobs(page = 1) {
            this.loading = true;
            fetch(`/api/jobs?page=${page}`)
                .then((response) => response.json())
                .then((data) => {
                    this.jobs = data.data;
                    this.links = data.links;
                    this.meta = data.meta;
                    this.currentPage = page;
                    this.loading = false;
                })
                .catch((error) => {
                    console.error("Error fetching jobs:", error);
                    this.loading = false;
                });
        },
        goToPage(link) {
            if (link.url) {
                const url = new URL(link.url);
                const page = url.searchParams.get("page");
                this.fetchJobs(page);
            }
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
.job-listing {
    margin-bottom: 2rem;
}
</style>
