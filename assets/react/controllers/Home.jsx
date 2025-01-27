import React, { useState } from 'react';
import { Layout } from "../components/Layout";
import { productCard } from "../components/ProductCard";
import { DataScroller } from "primereact/datascroller";
import styled from "styled-components";

const Main = styled.main`
    display: flex;
    justify-content: space-between;
    
    .p-datascroller {
        width: 100%;
    }
    
    @media screen and (max-width: 1023px) {
        flex-direction: column-reverse;
    }
`;

export default function Home(props) {
    const [products, setProducts] = useState(JSON.parse(props.products));
    return (
        <Layout user={props.user}>
            <Main>
                <DataScroller
                    value={products}
                    itemTemplate={(data) => productCard(data, props.user.id)}
                    inline
                    rows={999}
                    header="Lista Produktów"
                />
            </Main>
        </Layout>
    )
}
