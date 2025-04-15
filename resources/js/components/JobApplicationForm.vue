<template>
    <div class="card mb-4 sticky-top" style="top: 20px">
        <div class="card-header">
            <h3 class="h5 mb-0">Apply for this Job</h3>
        </div>
        <div class="card-body">
            <div
                v-if="success"
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                {{ success }}
                <button
                    type="button"
                    class="btn-close"
                    @click="success = null"
                    aria-label="Close"
                ></button>
            </div>

            <div
                v-if="generalError"
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >
                {{ generalError }}
                <button
                    type="button"
                    class="btn-close"
                    @click="generalError = null"
                    aria-label="Close"
                ></button>
            </div>

            <form @submit.prevent="submitApplication" v-if="!success">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name*</label>
                    <input
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': errors.name }"
                        id="name"
                        v-model="application.name"
                        required
                    />
                    <div v-if="errors.name" class="invalid-feedback">
                        {{ errors.name[0] }}
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Your Email*</label>
                    <input
                        type="email"
                        class="form-control"
                        :class="{ 'is-invalid': errors.email }"
                        id="email"
                        v-model="application.email"
                        required
                    />
                    <div v-if="errors.email" class="invalid-feedback">
                        {{ errors.email[0] }}
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cover_letter" class="form-label"
                        >Cover Letter</label
                    >
                    <textarea
                        class="form-control"
                        :class="{ 'is-invalid': errors.cover_letter }"
                        id="cover_letter"
                        v-model="application.cover_letter"
                        rows="3"
                    ></textarea>
                    <div v-if="errors.cover_letter" class="invalid-feedback">
                        {{ errors.cover_letter[0] }}
                    </div>
                </div>

                <div class="mb-3">
                    <label for="resume" class="form-label"
                        >Resume (PDF, DOC, DOCX)</label
                    >
                    <input
                        class="form-control"
                        :class="{ 'is-invalid': errors.resume }"
                        type="file"
                        id="resume"
                        @change="handleFileUpload"
                    />
                    <div v-if="errors.resume" class="invalid-feedback">
                        {{ errors.resume[0] }}
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                    :disabled="submitting"
                >
                    <span v-if="submitting">
                        <span
                            class="spinner-border spinner-border-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Applying...
                    </span>
                    <span v-else>Apply Now</span>
                </button>
            </form>

            <div v-else class="text-center">
                <a href="/jobs" class="btn btn-outline-primary">
                    Apply to Another Job
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
            application: {
                name: "",
                email: "",
                cover_letter: "",
                resume: null,
            },
            submitting: false,
            success: null,
            generalError: null,
            errors: {},
        };
    },
    methods: {
        handleFileUpload(event) {
            this.application.resume = event.target.files[0] || null;
        },
        submitApplication() {
            this.submitting = true;
            this.errors = {};
            this.generalError = null;

            const formData = new FormData();
            formData.append("name", this.application.name);
            formData.append("email", this.application.email);

            if (this.application.cover_letter) {
                formData.append("cover_letter", this.application.cover_letter);
            }

            if (this.application.resume) {
                formData.append("resume", this.application.resume);
            }

            // Add CSRF token
            formData.append(
                "_token",
                document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            );

            // Use the API endpoint instead of the web route
            fetch(`/api/jobs/${this.jobId}/apply`, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        if (response.status === 422) {
                            // Handle validation errors
                            return response.json().then((data) => {
                                throw { validationErrors: data.errors };
                            });
                        }
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        this.success =
                            data.message ||
                            "Your application has been submitted successfully!";
                    } else {
                        this.generalError =
                            data.message ||
                            "There was a problem with your application.";
                    }
                    this.submitting = false;
                })
                .catch((error) => {
                    this.submitting = false;

                    if (error.validationErrors) {
                        this.errors = error.validationErrors;
                        // If there's a more user-friendly message in the validation errors, show it
                        if (this.errors.email && this.errors.email[0]) {
                            this.generalError = this.errors.email[0];
                        }
                    } else {
                        this.generalError =
                            "An error occurred. Please try again.";
                        console.error("Error submitting application:", error);
                    }
                });
        },
        resetForm() {
            this.application = {
                name: "",
                email: "",
                cover_letter: "",
                resume: null,
            };
            this.success = null;
            this.generalError = null;
            this.errors = {};
        },
    },
};
</script>
