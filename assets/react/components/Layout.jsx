import { PrimeReactProvider } from 'primereact/api';
import { Navbar } from "./Navbar";

export function Layout({ children }) {
    return (
        <PrimeReactProvider>
            <Navbar />
            { children }
        </PrimeReactProvider>
    )
}