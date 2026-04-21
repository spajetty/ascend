/**
 * Batangas Province – Address Data
 * Structure: cityName → { district (1-4), barangays: [] }
 *
 * Barangays for cities other than Batangas City are abbreviated.
 * Expand as needed.
 */
const ADDRESS_DATA = {
    'Batangas City': {
        district: 1,
        barangays: [
            'Alangilan', "Arce (D' Arce)", 'Bagong Sikat', 'Bagumbayan',
            'Bilogo', 'Bolbok', 'Brave', 'Bukal', 'Calicanto', 'Cuta',
            'Dalig', 'Del Monte', 'Dela Paz (Pob.)', 'Domoclay', 'Dumantay',
            'Gulod Itaas', 'Gulod Labac', 'Ilihan (Pob.)', 'Kumintang Ibaba',
            'Kumintang Ilaya', 'Libjo (Libtong)', 'Liponpon, Isla Verde',
            'Maabab', 'Maalat', 'Magahis', 'Malalim', 'Malamig', 'Mangin',
            'Maruclap', 'Pagkilatan', 'Paharang Kanluran', 'Paharang Silangan',
            'Pallocan Kanluran', 'Pallocan Silangan', 'Pinamucan Ibaba',
            'Pinamucan Ilaya', 'Pinamucan Silangan', 'Poblacion Balagtas (Pob.)',
            'San Agapito (Pob.)', 'San Agustin Kanluran (Pob.)',
            'San Agustin Silangan (Pob.)', 'San Andres (Pob.)',
            'San Antonio (Pob.)', 'San Isidro (Pob.)', 'San Jose Sico',
            'San Miguel (Pob.)', 'San Pedro (Pob.)', 'Santa Clara (Pob.)',
            'Santa Rita Aplaya', 'Santa Rita Karsada', 'Santo Domingo',
            'Santo Niño', 'Simlong', 'Soro-soro Ibaba', 'Soro-soro Ilaya',
            'Soro-soro Kanluran', 'Talumpok Kanluran', 'Talumpok Silangan',
            'Tinga Itaas', 'Tinga Labac', 'Tingga Kanluran', 'Tingga Silangan',
            'Tulo', 'Wawa',
        ],
    },
    'Bauan': {
        district: 1,
        barangays: [
            'Alagao', 'Aplaya', 'As-is', 'Bagong Tubig', 'Bilog-bilog',
            'California', 'Calicanto', 'Catandaan', 'Cuta', 'Dungaw',
            'Durungao', 'Gulod', 'Ilat Kanluran', 'Ilat Silangan', 'Libjo',
            'Lipahan', 'Magahis', 'Malindig', 'Malusak', 'Manlayaan',
            'Mapulo', 'Mataas Lupa', 'Matingain I', 'Matingain II',
            'Palatasan', 'Pandan', 'Parang Parang', 'Pulo', 'San Miguel',
            'San Pedro', 'Santo Tomas', 'Sinala', 'Sitio Wawa', 'Taklang Anak',
        ],
    },
    'Calaca': {
        district: 1,
        barangays: [
            'Baclas', 'Bagong Tubig', 'Balimbing', 'Bambang', 'Bisaya',
            'Cahil', 'Calantas', 'Calatagan', 'Culianin', 'Dacanlao',
            'Dila', 'Lumbang', 'Mabini', 'Makina', 'Niyugan', 'Palanas',
            'Pantay', 'Sapang', 'Singcang',
        ],
    },
    'Ibaan': {
        district: 2,
        barangays: [
            'Bago', 'Balanga', 'Bungahan', 'Calamias', 'Catandaan', 'Cawongan',
            'Manghinao Proper', 'Manghinao Uno', 'Munting Tubig', 'Palindan',
            'Pangao', 'Pinagkawitan', 'Pinagtatagan', 'Poblacion', 'Quilo-quilo',
            'Saguing', 'San Carlos', 'San Francisco', 'Talisay', 'Talumpok',
            'Tubigan', 'Tulay',
        ],
    },
    'Lemery': {
        district: 2,
        barangays: [
            'Bagong Pook', 'Balangon', 'Batas', 'Bilibinwang', 'Bukal',
            'Cahil', 'Calamias', 'Calumpang', 'Carasuchi', 'Garcia',
            'Inabanga', 'Kalayaan', 'Kapatagan', 'Katuman', 'Mabini',
            'Makina', 'Maligaya', 'Mataas', 'Poblacion 1', 'Poblacion 2',
            'Poblacion 3', 'Poblacion 4', 'San Juan', 'San Piro', 'Sapang',
        ],
    },
    'Lian': {
        district: 1,
        barangays: [
            'Bagong Pook', 'Balibago', 'Binubusan', 'Bungahan', 'Cumba',
            'Humayingan', 'Kapito', 'Liwanag', 'Lutuan', 'Magatol',
            'Maguibuay', 'Mahayahay', 'Matabungkay', 'Prenza', 'Pugad Lawin',
            'Punta', 'San Carlos', 'San Miguel', 'Santo Niño', 'Talisay',
        ],
    },
    'Lipa City': {
        district: 3,
        barangays: [
            'Adya', 'Anilao', 'Anilao-Labac', 'Antipolo del Norte',
            'Antipolo del Sur', 'Bagong Pook', 'Balintawak', 'Banaybanay',
            'Bolbok', 'Bulaklak', 'Bulacnin', 'Bulaon', 'Calamias', 'Cumba',
            'Dagatan', 'Duhatan', 'Halang', 'Inosloban', 'Kayumanggi',
            'Latag', 'Lodlod', 'Lumbang', 'Mabini', 'Malagonlong', 'Malitlit',
            'Marauoy', 'Marawoy', 'Maypa', 'Mataas na Lupa', 'Munting Pulo',
            'Pagolingin Bata', 'Pagolingin East', 'Pagolingin West',
            'Pangao', 'Pinagkawitan', 'Pinagtongulan', 'Plaridel', 'Poblacion',
            'Quezon', 'Sabang', 'San Benito', 'San Carlos', 'San Celestino',
            'San Francisco', 'San Guillermo', 'San Jose', 'San Salvador',
            'San Sebastian', 'Santo Niño', 'Tangob', 'Tanguay', 'Tibig',
            'Tipacan',
        ],
    },
    'Lobo': {
        district: 1,
        barangays: [
            'Balibago', 'Barangay 1 (Pob.)', 'Barangay 2 (Pob.)',
            'Barangay 3 (Pob.)', 'Barangay 4 (Pob.)', 'Bukal', 'Calamias',
            'Candidnahan', 'Catandaan', 'Hinulugang', 'Ilog', 'Mabato',
            'Malabrigo', 'Malalim', 'Masaguitsit', 'Nagtalongtong',
            'Navotas', 'Pitogo', 'San Miguel', 'Santa Ana', 'Sawang',
        ],
    },
    'Mabini': {
        district: 1,
        barangays: [
            'Bagong Silang', 'Balagtas', 'Balibago', 'Banahaw', 'Bulaklak',
            'Bulaon', 'Calamias', 'Catandaan', 'Cawongan', 'Hulog',
            'Luyahan', 'Mabio', 'Mainit', 'Majawak', 'Mataas',
            'Nabunturan', 'Natulo', 'Niogan', 'Palahanan I', 'Palahanan II',
            'Palsara', 'Peña', 'Pumunta', 'Pulang-bato', 'San Isidro',
            'Sampalocan', 'San Juan', 'Sulok', 'Tingas',
        ],
    },
    'Malvar': {
        district: 3,
        barangays: [
            'Bagong Pook', 'Batas', 'Bulalo', 'Luta del Norte', 'Luta del Sur',
            'Poblacion', 'San Andres', 'San Fernando', 'San Gregorio',
            'San Isidro', 'Santa Cruz', 'Santa Elena',
        ],
    },
    'Mataas na Kahoy': {
        district: 2,
        barangays: [
            'Bayorbor', 'Bubuyan', 'Calingatan', 'Kinalaglagan', 'Looc',
            'Lumbang Calzada', 'Lumbang Kanluran', 'Poblacion', 'Sa Ilaya',
            'Tubig Kambing', 'Tubig Maso',
        ],
    },
    'Nasugbu': {
        district: 1,
        barangays: [
            'Aga', 'Balaytigui', 'Banilad', 'Bilaran', 'Bucana',
            'Bulihan', 'Bungahan', 'Calayo', 'Catandaan', 'Cogunan',
            'Dayap', 'Gulod', 'Kaylaway', 'Kayrilaw', 'Labac',
            'Latag', 'Looc', 'Lumbangan', 'Malapad na Bato', 'Maugat',
            'Munting Burol', 'Munting Indan', 'Natipuan', 'Pantalan',
            'Papaya', 'Populated', 'Putol', 'Reparo', 'Tumalim', 'Utod',
            'Wawa',
        ],
    },
    'San Jose': {
        district: 2,
        barangays: [
            'Bagong Pook', 'Banaybanay', 'Coloconto', 'Dagatan', 'Inao-awan',
            'Kalangitan', 'Katiis', 'Lumbang', 'Mahabang Parang', 'Mataasnakahoy',
            'Munting Tubig', 'Pangao', 'Papaya', 'Poblacion', 'Salamanca',
            'San Agustin', 'San Antonio', 'San Isidro', 'San Jose', 'San Roque',
            'Santo Niño', 'Tangob', 'Taysan',
        ],
    },
    'San Juan': {
        district: 4,
        barangays: [
            'Anas', 'Artaga', 'Bae', 'Baybayin', 'Bulsa', 'Calubcub Dos',
            'Calubcub Uno', 'Catandaan', 'Concepcion', 'Imelda', 'Laiya-Aplaya',
            'Laiya-Ibabao', 'Libato', 'Lobo', 'Magahis', 'Malabrigo',
            'Malapad', 'Munting Coral', 'Nagsaulay', 'Pinagsakahan',
            'Poblacion', 'San Isidro', 'San Pedro', 'Santol', 'Talibayog',
            'Talisay', 'Tambo', 'Tandang Sora',
        ],
    },
    'San Luis': {
        district: 2,
        barangays: [
            'Bagong Tubig', 'Bungahan', 'Cawongan', 'Hatol', 'Lapu-lapu',
            'Lumil', 'Mayana', 'Munting Ilog', 'Nag-ilog', 'Nangkaan',
            'Poblacion', 'San Antonio', 'San Juan', 'Santo Niño', 'Tipacan',
        ],
    },
    'San Nicolas': {
        district: 2,
        barangays: [
            'Calangay', 'Caluangan', 'Katmon', 'Lumbangan', 'Makina',
            'Malaking Pulo', 'Maugat West', 'Munting Pulo', 'Poctol',
            'Poblacion', 'Punta', 'San Nicolas Proper', 'Tubig',
        ],
    },
    'San Pascual': {
        district: 4,
        barangays: [
            'Abo', 'Alitagtag', 'Balimbing', 'Banyaga', 'Bukal',
            'Calamias', 'Daan', 'Dagatan', 'Dao', 'Guinhawa', 'Jaybanga',
            'Lagadlarin', 'Mabini', 'Magsaysay', 'Mataas', 'Nag-ilog',
            'Natunuan North', 'Natunuan South', 'Pag-asa', 'Panaytayan',
            'Pantay', 'Pulong Anahaw', 'Quisumbing', 'Saimsim', 'Sampaloc',
            'San Nicolas', 'San Roque', 'Sinipian', 'Talangnan',
            'Talisay', 'Tambo', 'Taysan',
        ],
    },
    'Santa Teresita': {
        district: 2,
        barangays: [
            'Antipolo', 'Balagtas', 'Batas', 'Burol', 'Carretunan',
            'Poblacion', 'San Marcos', 'Santol', 'Taisan', 'Tuy',
        ],
    },
    'Santo Tomas': {
        district: 3,
        barangays: [
            'Balibago', 'Banaba East', 'Banaba Middle', 'Banaba West',
            'Banaba-Rapu', 'Barangay 1 (Pob.)', 'Barangay 2 (Pob.)',
            'Barangay 3 (Pob.)', 'Barangay 4 (Pob.)', 'Bilog-bilog',
            'Calumpang', 'Dahilig', 'Dalugan', 'Halang', 'Luta', 'Magabe',
            'Makiling', 'Malaking Pulo', 'Mataactac', 'Natunuan',
            'Pandayan', 'Poblacion', 'Pulangbato', 'Sampaloc', 'San Agustin',
            'San Antonio', 'San Fernando', 'San Pedro',  'Tulo',
        ],
    },
    'Taal': {
        district: 2,
        barangays: [
            'Apacay', 'Aya', 'Bagbag', 'Calihawan', 'Carasuche', 'Cawit',
            'Caysasay', 'Cubamba', 'Cultihan', 'Gahol', 'Halang',
            'Ilog-Ilog', 'Luntal', 'Mahabang Lodlod', 'Niogan',
            'Pansol', 'Pile', 'Poblacion 1 (Kapitan Kong)', 'Poblacion 2 (Lapulapu)',
            'Poblacion 3 (Gregorio)', 'Poblacion 4 (Soliman)',
            'Poblacion 5 (Bagumbayan)', 'Pook', 'Quilitisan', 'Real',
            'Sambat', 'San Agustin', 'Santissima Trinidad', 'Tatlong Maria',
            'Tierra Alta',
        ],
    },
    'Tanauan City': {
        district: 3,
        barangays: [
            'Alitagtag', 'Ambulong', 'Bagbag', 'Bagumbayan',
            'Balele', 'Bulagao', 'Calamba', 'Cale', 'Dao',
            'Darasa', 'Gonzales', 'Hidalgo', 'Janopol', 'Janopol Oriental',
            'Laurel', 'Luyos', 'Mabini', 'Malaking Pulo', 'Maria Paz',
            'Maugat', 'Montaña', 'Natatas', 'Pagaspas', 'Pantay Matanda',
            'Pantay Bata', 'Pook', 'Poblacion 1', 'Poblacion 2',
            'Poblacion 3', 'Poblacion 4', 'Poblacion 5', 'Poblacion 6',
            'Poblacion 7', 'Sala', 'Sampaga', 'San Jose', 'San Juan',
            'San Rafael', 'Santol', 'Santor', 'Sulpoc', 'Sulsuguin',
            'Talaga', 'Tinurik', 'Trapiche', 'Ulango', 'Wawa',
        ],
    },
    'Taysan': {
        district: 2,
        barangays: [
            'Bataan', 'Bayanan', 'Bulihan', 'Burol', 'Guinhawa',
            'Lagnas', 'Lumbang Galamay', 'Lumbang na Iba', 'Mabini',
            'Manggahan', 'Niogan na Labak', 'Niogan na Nasa Itaas',
            'Pansol', 'Pignabuang', 'Poblacion', 'Sambat', 'San Antonio',
            'Sinala',
        ],
    },
    'Tingloy': {
        district: 1,
        barangays: [
            'Barangay 1 (Maricaban)', 'Barangay 2 (Poblacion)', 'Barangay 3',
            'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7',
            'Mabini', 'Papaya', 'Talisay',
        ],
    },
    'Tuy': {
        district: 1,
        barangays: [
            'Acle', 'Bayudbud', 'Bolbok', 'Bukal', 'Dalig',
            'Dao', 'Guinhawa', 'Lumbangan', 'Mabini', 'Nañasin',
            'Niyugan', 'Poblacion', 'Quilitisan', 'Real', 'Saimsim',
            'Sampalucan', 'Santol', 'Taysan', 'Tunggain',
        ],
    },
};
