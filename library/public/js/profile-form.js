/**
 * Profile form handler for refreshing after photo upload
 * Add this script to the profile update page to notify other pages of changes
 */
document.addEventListener('DOMContentLoaded', function() {
    // Mark that we're on the profile page
    sessionStorage.setItem('onProfilePage', 'true');
    
    // When the form is submitted
    const photoInput = document.querySelector('input[type="file"][wire\\:model="photo"]');
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            // If a file is selected, mark that the profile is being updated
            if (this.files && this.files.length > 0) {
                localStorage.setItem('profilePhotoUpdating', 'true');
            }
        });
    }
    
    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('saved', () => {
            // Mark that profile was updated
            localStorage.setItem('lastProfileUpdate', Date.now());
            
            // If we previously marked a photo update
            if (localStorage.getItem('profilePhotoUpdating')) {
                localStorage.removeItem('profilePhotoUpdating');
                
                // No need to force refresh here - we'll do it when returning to other pages
            }
        });
    });
});
