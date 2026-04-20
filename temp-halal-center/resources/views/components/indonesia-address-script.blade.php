<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addressSelectors = {
            province: 'select[name="provinsi"]',
            regency: 'select[name="kab_kota"]',
            district: 'select[name="kecamatan"]',
            village: 'select[name="kelurahan_desa"]'
        };

        const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        async function fetchData(url) {
            try {
                const response = await fetch(url);
                return await response.json();
            } catch (error) {
                console.error('Error fetching address data:', error);
                return [];
            }
        }

        async function initAddressDropdowns(container = document) {
            const provinceSelect = container.querySelector(addressSelectors.province);
            const regencySelect = container.querySelector(addressSelectors.regency);
            const districtSelect = container.querySelector(addressSelectors.district);
            const villageSelect = container.querySelector(addressSelectors.village);

            if (!provinceSelect) return;

            // Load Provinces
            const provinces = await fetchData(`${baseUrl}/provinces.json`);
            provinces.forEach(prob => {
                const option = document.createElement('option');
                option.value = prob.name;
                option.dataset.id = prob.id;
                option.textContent = prob.name;
                provinceSelect.appendChild(option);
            });

            // Set initial values if they exist (for edit mode)
            const initialProvince = provinceSelect.dataset.initial;
            if (initialProvince) {
                provinceSelect.value = initialProvince;
                await provinceSelect.dispatchEvent(new Event('change'));
            }

            provinceSelect.addEventListener('change', async function() {
                const selectedOption = this.options[this.selectedIndex];
                const provinceId = selectedOption.dataset.id;

                // Clear children
                regencySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

                if (!provinceId) return;

                const regencies = await fetchData(`${baseUrl}/regencies/${provinceId}.json`);
                regencies.forEach(reg => {
                    const option = document.createElement('option');
                    option.value = reg.name;
                    option.dataset.id = reg.id;
                    option.textContent = reg.name;
                    regencySelect.appendChild(option);
                });

                const initialRegency = regencySelect.dataset.initial;
                if (initialRegency) {
                    regencySelect.value = initialRegency;
                    regencySelect.removeAttribute('data-initial');
                    await regencySelect.dispatchEvent(new Event('change'));
                }
            });

            regencySelect.addEventListener('change', async function() {
                const selectedOption = this.options[this.selectedIndex];
                const regencyId = selectedOption.dataset.id;

                districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

                if (!regencyId) return;

                const districts = await fetchData(`${baseUrl}/districts/${regencyId}.json`);
                districts.forEach(dist => {
                    const option = document.createElement('option');
                    option.value = dist.name;
                    option.dataset.id = dist.id;
                    option.textContent = dist.name;
                    districtSelect.appendChild(option);
                });

                const initialDistrict = districtSelect.dataset.initial;
                if (initialDistrict) {
                    districtSelect.value = initialDistrict;
                    districtSelect.removeAttribute('data-initial');
                    await districtSelect.dispatchEvent(new Event('change'));
                }
            });

            districtSelect.addEventListener('change', async function() {
                const selectedOption = this.options[this.selectedIndex];
                const districtId = selectedOption.dataset.id;

                villageSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

                if (!districtId) return;

                const villages = await fetchData(`${baseUrl}/villages/${districtId}.json`);
                villages.forEach(vill => {
                    const option = document.createElement('option');
                    option.value = vill.name;
                    option.dataset.id = vill.id;
                    option.textContent = vill.name;
                    villageSelect.appendChild(option);
                });

                const initialVillage = villageSelect.dataset.initial;
                if (initialVillage) {
                    villageSelect.value = initialVillage;
                    villageSelect.removeAttribute('data-initial');
                }
            });
        }

        // Initialize for static forms
        initAddressDropdowns();

        // If there's a modal, we might need to re-init or it's already there
        // For dynamic forms or modals that are always in DOM, it should work.
    });
</script>
