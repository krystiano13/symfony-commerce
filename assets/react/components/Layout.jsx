import { PrimeReactProvider } from 'primereact/api';
import { Navbar } from "./Navbar";

export function Layout({ children, user }) {
    return (
        <PrimeReactProvider>
            <Navbar user={user} />
            { children }
        </PrimeReactProvider>
    )
}