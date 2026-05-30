import { ref, watchEffect } from 'vue'

// Calculates when a review is made compared to the movies release date
function getPeriodLabel(dateStr, releaseDate) {
    if (!dateStr) return 'Unknown'

    const reviewDate = new Date(dateStr)
    const release = new Date(releaseDate) // JS converts Date to a timestamp in milliseconds  
    const diffMilliseconds = reviewDate - release
    const diffDays = diffMilliseconds / (1000 * 60 * 60 * 24) // Milliseconds -> Days

    if (diffDays < 0) {
        return 'Pre-release'
    }     

    if (diffDays <= 3) {
        return 'Opening weekend'
    }    

    if (diffDays <= 7) {
        return 'Opening week'
    }

    if (diffDays <= 14) {
        return 'First two weeks'
    }

    if (diffDays <= 30) {
        return 'First Month'
    }

    if (diffDays <= 90) {
        return 'First 3 months'
    }

    if (diffDays <= 180) {
        return 'First 6 months'
    }

    if (diffDays <= 365) { 
        return 'First year'
    }

    if (diffDays <= 365 * 3) { 
        return '1-3 years later'
    }
    
    return '3+ years later'
}

// Converts text rating to integers
function ratingToScore(rating) {
    const map = {
        'Peak': 5,
        'So bad it\'s good': 3.5,
        'Mid at best': 2.5,
        'Trash': 1,
    }
    return map[rating] ?? null
}

// The Composable Function = A function which exists independently and can be reused across any components 
// Takes reviews and release date, and creates a snapshot ref. When called, a reactive snapshots array is returned
// Transforms the reviews array into a dataset that a graph component can read and draw
export function useReviewTimeline(reviews, releaseDate) {
    const snapshots = ref([])

    // Watches and automatically tracks values, re-running the function automatically when they get changed 
    watchEffect(() => {
        const periodOrder = [
            'Pre-release',
            'Opening weekend',
            'Opening week',
            'First two weeks',
            'First month',
            'First 3 months',
            'First 6 months',
            'First year',
            '1–3 years later',
            '3+ years later'
        ]

        const groups = {}
        for (const review of reviews.value) {
            const label = getPeriodLabel(review.created_at, releaseDate.value)
            if (!groups[label]) {
                groups[label] = []
            }
            groups[label].push(review)
        }

        const result = []
        for (const period of periodOrder) {
            if (!groups[period]) continue

            const periodReviews = groups[period]

            const scores = periodReviews
                .map(r => ratingToScore(r.rating))
                .filter(s => s !== null)

            const avgScore = scores.length
                ? scores.reduce((a, b) => a + b, 0) / scores.length
                : null

            const avgPlot = average(periodReviews.map(r => r.plot).filter(Boolean))
            const avgActing = average(periodReviews.map(r => r.acting).filter(Boolean))
            const avgPacing = average(periodReviews.map(r => r.pacing).filter(Boolean))

            const samples = periodReviews
                .filter(r => r.content)
                .slice(0, 2)
                .map(r => ({
                    username: r.username,
                    content: r.content.substring(0, 120) + (r.content.length > 120 ? '...': ''),
                    rating: r.rating
                }))

            result.push({
                period,
                reviewCount: periodReviews.length,
                avgScore,
                avgPlot,
                avgActing,
                avgPacing,
                samples
            })
        }

        snapshots.value = result
    })

    return {snapshots}
}


function average(arr) {
    if (!arr.length) {
        return null
    }
    return arr.reduce((a, b) => a + b, 0) / arr.length
}