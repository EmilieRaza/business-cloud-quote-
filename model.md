ROLE_AGENT
ROLE_ADMIN

User:
    description: text
    photo: File
    address: UserAddress
    quotationRequests: list<QuotationRequest>

QuotationRequest:
    // Vos coordonnées
    contactFirstname: string
    contactLastname: string
    contactPhone: string
    contactEmail: string
    // Coordonnées du défunt
    deceasedFirstname: string
    deceasedLastname: string
    deceasedAddress:
    
    // Lieu du décès: Nom de l'établissement ou adresse
    deathPlace: string
    
    // Type d'obsèques
    funeralType: string choice
    [
        'Crémation' => 'Crémation',
        'Inhumation' => 'Inhumation',
    ]
    
    
    // Si crémation: Destination des cendres. Dispersion au jardin du souvenir / Cimetière)
    ashesDestination: string 
    // Si enterrement: Lieu cimetière souhaité si ils ont une concession familiale
    burialDestination: string
    
    // Recueillement
    contemplation: string choice
    [
        'Civil' => 'Civil',
        'Religieux' => 'Religieux',
    ]

    agent: User
    
File:
UserAddress:
ContactUs
    firstname:
    lastname:
    email:
    phone:
    subject:
    message:
    createdAt: