import { PrimeReactProvider } from 'primereact/api';

export function Layout({ children }) {
    return (
        <PrimeReactProvider>
            { children }
        </PrimeReactProvider>
    )
}