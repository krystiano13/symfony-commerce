import React from 'react';
import { Layout } from "../components/Layout";
import { productCard } from "../components/ProductCard";
import { DataScroller } from "primereact/datascroller";
import { Card } from "primereact/card";
import styled from "styled-components";

const Filters = styled.div`
    margin-top: 1rem;
    position: sticky;
    top: 1rem;
    height: 30rem;
    width: 33%;

    @media screen and (max-width: 1023px) {
        width: 100%;
        position: static;
    }
`;

const Main = styled.main`
    display: flex;
    justify-content: space-between;
    
    @media screen and (max-width: 1023px) {
        flex-direction: column-reverse;
    }
`;

export default function Home(props) {
    const products = [
        {
            id: '1000',
            code: 'f230fh0g3',
            name: 'Bamboo Watch',
            description: 'Product Description',
            image: 'bamboo-watch.jpg',
            price: 65,
            category: 'Accessories',
            quantity: 24,
            inventoryStatus: 'INSTOCK',
            rating: 5
        },
        {
            id: '1001',
            code: 'nvklal433',
            name: 'Black Watch',
            description: 'Product Description',
            image: 'black-watch.jpg',
            price: 72,
            category: 'Accessories',
            quantity: 61,
            inventoryStatus: 'INSTOCK',
            rating: 4
        },
        {
            id: '1001',
            code: 'nvklal433',
            name: 'Black Watch',
            description: 'Product Description',
            image: 'black-watch.jpg',
            price: 72,
            category: 'Accessories',
            quantity: 61,
            inventoryStatus: 'INSTOCK',
            rating: 4
        },
    ]
    return (
        <Layout user={props.user}>
            <Main>
                <DataScroller
                    value={products}
                    itemTemplate={productCard}
                    inline
                    rows={999}
                    header="Lista Produktów"
                />
                <Filters>
                    <Card
                        title="Filtry"
                        style={{ width: "100%", height: "100%" }}
                    >
                    </Card>
                </Filters>
            </Main>
        </Layout>
    )
}
