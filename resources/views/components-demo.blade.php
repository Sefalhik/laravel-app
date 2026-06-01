<x-app-layout title="Démo composants">

    <h2 class="text-xl font-bold text-gray-700 mb-4">Alerts</h2>
    <div class="space-y-3 mb-8">
        <x-alert type="success">Opération réussie !</x-alert>
        <x-alert type="error">Email invalide !</x-alert>
        <x-alert type="warning">Attention, action irréversible.</x-alert>
        <x-alert type="success" :dismissible="true">Je suis dismissible — clique la croix !</x-alert>
        <x-alert type="error" :dismissible="true">Erreur dismissible.</x-alert>
        <x-alert type="warning" :dismissible="true">Avertissement dismissible.</x-alert>
    </div>

    <h2 class="text-xl font-bold text-gray-700 mb-4">Cards</h2>
    <div class="space-y-4 mb-8">
        <x-card title="Card avec titre">
            <p>Contenu de la carte avec un badge : <x-badge color="green">Actif</x-badge></p>
        </x-card>
        <x-card>
            <p>Card sans titre.</p>
        </x-card>
    </div>

    <h2 class="text-xl font-bold text-gray-700 mb-4">Badges</h2>
    <div class="flex gap-2 mb-8">
        <x-badge>green (défaut)</x-badge>
        <x-badge color="red">red</x-badge>
        <x-badge color="blue">blue</x-badge>
    </div>

    <h2 class="text-xl font-bold text-gray-700 mb-4">Buttons</h2>
    <div class="flex gap-2 mb-8">
        <x-button variant="primary">Valider</x-button>
        <x-button variant="danger">Supprimer</x-button>
        <x-button variant="primary" href="#">Lien primary</x-button>
        <x-button variant="danger" href="#">Lien danger</x-button>
    </div>

    <h2 class="text-xl font-bold text-gray-700 mb-4">Modal</h2>
    <x-modal title="Confirmation de suppression">
        <x-slot:trigger>
            <x-button variant="danger">Supprimer l'élément</x-button>
        </x-slot:trigger>
        <x-slot:content>
            <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.</p>
            <div class="mt-6 flex justify-end gap-2">
                <x-button variant="primary">Confirmer</x-button>
            </div>
        </x-slot:content>
    </x-modal>

</x-app-layout>
