"use strict";

console.log('DISABLED_FEATURES', DISABLED_FEATURES);
console.log('DISABLED_FEATURE_ACTIVE_CONTROLLER', DISABLED_FEATURE_ACTIVE_CONTROLLER);

if (typeof DISABLED_FEATURES !== "undefined") {
    if (DISABLED_FEATURE_ACTIVE_CONTROLLER === "dashboard") {
        // Add custom tags
        const features = ["feature-invoices", "feature-estimates", "feature-proposals",];

        if (document.querySelectorAll(".home-summary > div").length > 2) {
            document
                .querySelectorAll(".home-summary > div")
                .forEach(function (element, index) {
                    if (element) {
                        element.classList.add(features[index]);
                    }
                });
        }

        const invoicesTotal = document.getElementById("invoices_total");
        if (invoicesTotal) {
            invoicesTotal.classList.add("feature-invoices");
        }

        for (let index = 0; index < DISABLED_FEATURES.length; index++) {
            const feature = DISABLED_FEATURES[index];
            // Remove from top stats
            document
                .querySelectorAll(`.quick-stats-${feature}`)
                .forEach(function (element) {
                    if (element) {
                        element.remove();
                    }
                });

            // Remove from finance
            document
                .querySelectorAll(`.feature-${feature}`)
                .forEach(function (element) {
                    if (element) {
                        element.remove();
                    }
                });

            // Remove from user data tab
            document
                .querySelectorAll(`#home_tab_${feature}, #home_my_${feature}`)
                .forEach(function (element) {
                    if (element) element.remove();
                });

            document
                .querySelectorAll(`[aria-controls="home_tab_${feature}"], [aria-controls="home_my_${feature}"]`)
                .forEach(function (element) {
                    const parentElement = element.parentNode;
                    if (parentElement) {
                        parentElement.remove();
                    }
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Trigger click on first item
            const firstNavItem = document.querySelector(".nav-tabs li:first-of-type a");
            if (firstNavItem) {
                firstNavItem.click();
            }

            // Remove top widget if empty
            setTimeout(() => {
                const topStatsWidget = document.getElementById("widget-top_stats");
                if (topStatsWidget && document.querySelectorAll("#widget-top_stats > .row div").length === 0) {
                    topStatsWidget.remove();
                }
            }, 500);
        });
    }

    if (DISABLED_FEATURES.includes("projects")) {
        let projectCard = $(".staff_projects_filter");
        if (projectCard.length) {
            if (projectCard.parent().hasClass("panel-table-full")) {
                // Profile view
                projectCard.parent().parent().find("h4").remove();
                projectCard.parent().remove();
            } else {
                // Profile edit
                let headings = $(".small-table-right-col > h4");
                $(".small-table-right-col .panel_s").each((i, card) => {
                    let selector = $(card).find(".staff_projects_filter");
                    if (selector.length) {
                        $(card).remove();
                        $(headings[i]).remove();
                    }
                });
            }
        }
    }
}
