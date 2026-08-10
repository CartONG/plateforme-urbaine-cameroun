--
-- PostgreSQL database dump
--

\restrict nIcO9yWgD4L849rakT5t5XPzWgXJha2g8tCv3hHbpXxGZ6BM4G4CtTXHNLZZrOX

-- Dumped from database version 16.14
-- Dumped by pg_dump version 16.14

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: divercity; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA divercity;


--
-- Name: hstore; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS hstore WITH SCHEMA public;


--
-- Name: EXTENSION hstore; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION hstore IS 'data type for storing sets of (key, value) pairs';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry and geography spatial types and functions';


SET default_table_access_method = heap;

--
-- Name: blocked_period; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.blocked_period (
    id integer NOT NULL,
    space_id uuid NOT NULL,
    created_by integer,
    date date NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    reason character varying(255),
    is_unblocked boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    CONSTRAINT chk_blocked_period_time CHECK ((end_time > start_time))
);


--
-- Name: COLUMN blocked_period.space_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.blocked_period.space_id IS '(DC2Type:uuid)';


--
-- Name: blocked_period_id_seq; Type: SEQUENCE; Schema: divercity; Owner: -
--

CREATE SEQUENCE divercity.blocked_period_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: blocked_period_id_seq; Type: SEQUENCE OWNED BY; Schema: divercity; Owner: -
--

ALTER SEQUENCE divercity.blocked_period_id_seq OWNED BY divercity.blocked_period.id;


--
-- Name: booking; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.booking (
    id uuid NOT NULL,
    space_id uuid NOT NULL,
    user_id integer NOT NULL,
    processing_user_id integer,
    status_id integer NOT NULL,
    event_activity_type_id integer,
    information_source_id integer,
    title character varying(200),
    last_name character varying(100) NOT NULL,
    first_name character varying(100) NOT NULL,
    organization character varying(150),
    email character varying(150) NOT NULL,
    phone character varying(30) NOT NULL,
    booking_purpose text NOT NULL,
    date date NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    participant_count integer NOT NULL,
    additional_information text,
    refusal_reason text,
    cancellation_reason text,
    submitted_at timestamp(0) without time zone NOT NULL,
    processed_at timestamp(0) without time zone,
    CONSTRAINT booking_participant_count_check CHECK ((participant_count > 0)),
    CONSTRAINT chk_booking_email CHECK (((email)::text ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'::text)),
    CONSTRAINT chk_booking_time CHECK ((end_time > start_time))
);


--
-- Name: COLUMN booking.id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.booking.id IS '(DC2Type:uuid)';


--
-- Name: COLUMN booking.space_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.booking.space_id IS '(DC2Type:uuid)';


--
-- Name: booking_media_object; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.booking_media_object (
    booking_id uuid NOT NULL,
    media_object_id integer NOT NULL
);


--
-- Name: COLUMN booking_media_object.booking_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.booking_media_object.booking_id IS '(DC2Type:uuid)';


--
-- Name: booking_resource; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.booking_resource (
    booking_id uuid NOT NULL,
    resource_id uuid NOT NULL
);


--
-- Name: COLUMN booking_resource.booking_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.booking_resource.booking_id IS '(DC2Type:uuid)';


--
-- Name: COLUMN booking_resource.resource_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.booking_resource.resource_id IS '(DC2Type:uuid)';


--
-- Name: event_activity_favorite; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.event_activity_favorite (
    id uuid NOT NULL,
    space_id uuid NOT NULL,
    user_id integer NOT NULL,
    media_object_id integer,
    title character varying(200) NOT NULL,
    start_date date,
    end_date date,
    description text,
    created_at timestamp(0) without time zone NOT NULL,
    CONSTRAINT chk_favorite_dates CHECK (((end_date IS NULL) OR (end_date >= start_date)))
);


--
-- Name: COLUMN event_activity_favorite.id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.event_activity_favorite.id IS '(DC2Type:uuid)';


--
-- Name: COLUMN event_activity_favorite.space_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.event_activity_favorite.space_id IS '(DC2Type:uuid)';


--
-- Name: event_activity_type; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.event_activity_type (
    id integer NOT NULL,
    label character varying(150) NOT NULL
);


--
-- Name: event_activity_type_id_seq; Type: SEQUENCE; Schema: divercity; Owner: -
--

CREATE SEQUENCE divercity.event_activity_type_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: event_activity_type_id_seq; Type: SEQUENCE OWNED BY; Schema: divercity; Owner: -
--

ALTER SEQUENCE divercity.event_activity_type_id_seq OWNED BY divercity.event_activity_type.id;


--
-- Name: information_source; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.information_source (
    id integer NOT NULL,
    label character varying(150) NOT NULL
);


--
-- Name: information_source_id_seq; Type: SEQUENCE; Schema: divercity; Owner: -
--

CREATE SEQUENCE divercity.information_source_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: information_source_id_seq; Type: SEQUENCE OWNED BY; Schema: divercity; Owner: -
--

ALTER SEQUENCE divercity.information_source_id_seq OWNED BY divercity.information_source.id;


--
-- Name: notification; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.notification (
    id integer NOT NULL,
    booking_id uuid NOT NULL,
    user_id integer,
    type character varying(50) NOT NULL,
    sent_at timestamp(0) without time zone NOT NULL,
    content text
);


--
-- Name: COLUMN notification.booking_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.notification.booking_id IS '(DC2Type:uuid)';


--
-- Name: notification_id_seq; Type: SEQUENCE; Schema: divercity; Owner: -
--

CREATE SEQUENCE divercity.notification_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: notification_id_seq; Type: SEQUENCE OWNED BY; Schema: divercity; Owner: -
--

ALTER SEQUENCE divercity.notification_id_seq OWNED BY divercity.notification.id;


--
-- Name: space; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.space (
    id uuid NOT NULL,
    created_by integer,
    updated_by integer,
    geo_data_id integer,
    name character varying(150) NOT NULL,
    description text,
    max_capacity integer NOT NULL,
    contact character varying(50),
    email character varying(150),
    video_link character varying(500),
    equipment text,
    slug character varying(128),
    is_validated boolean NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    CONSTRAINT space_max_capacity_check CHECK ((max_capacity > 0))
);


--
-- Name: COLUMN space.id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.space.id IS '(DC2Type:uuid)';


--
-- Name: space_admin; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.space_admin (
    user_id integer NOT NULL,
    space_id uuid NOT NULL,
    assigned_at timestamp(0) without time zone NOT NULL
);


--
-- Name: COLUMN space_admin.space_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.space_admin.space_id IS '(DC2Type:uuid)';


--
-- Name: space_media_object; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.space_media_object (
    space_id uuid NOT NULL,
    media_object_id integer NOT NULL
);


--
-- Name: COLUMN space_media_object.space_id; Type: COMMENT; Schema: divercity; Owner: -
--

COMMENT ON COLUMN divercity.space_media_object.space_id IS '(DC2Type:uuid)';


--
-- Name: status; Type: TABLE; Schema: divercity; Owner: -
--

CREATE TABLE divercity.status (
    id integer NOT NULL,
    code character varying(30) NOT NULL,
    label character varying(100) NOT NULL
);


--
-- Name: status_id_seq; Type: SEQUENCE; Schema: divercity; Owner: -
--

CREATE SEQUENCE divercity.status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: status_id_seq; Type: SEQUENCE OWNED BY; Schema: divercity; Owner: -
--

ALTER SEQUENCE divercity.status_id_seq OWNED BY divercity.status.id;


--
-- Name: actor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.actor (
    id uuid NOT NULL,
    logo_id integer,
    created_by integer,
    updated_by integer,
    name character varying(255) NOT NULL,
    acronym character varying(255),
    category character varying(255) NOT NULL,
    description text,
    office_name character varying(255) DEFAULT NULL::character varying,
    office_address character varying(255) DEFAULT NULL::character varying,
    contact_name character varying(255) DEFAULT NULL::character varying,
    contact_position character varying(255) DEFAULT NULL::character varying,
    website character varying(255) DEFAULT NULL::character varying,
    phone character varying(255) DEFAULT NULL::character varying,
    email character varying(255) DEFAULT NULL::character varying,
    external_images text,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    slug character varying(128) DEFAULT NULL::character varying,
    is_validated boolean NOT NULL,
    geo_data_id integer,
    creator_message text,
    other_category character varying(255) DEFAULT NULL::character varying,
    other_thematic character varying(255) DEFAULT NULL::character varying,
    administrative_scopes text NOT NULL,
    thematics text,
    odds json,
    banoc character varying(128) DEFAULT NULL::character varying,
    banoc_url character varying(128) DEFAULT NULL::character varying
);


--
-- Name: COLUMN actor.id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor.id IS '(DC2Type:uuid)';


--
-- Name: COLUMN actor.external_images; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor.external_images IS '(DC2Type:simple_array)';


--
-- Name: COLUMN actor.administrative_scopes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor.administrative_scopes IS '(DC2Type:simple_array)';


--
-- Name: COLUMN actor.thematics; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor.thematics IS '(DC2Type:simple_array)';


--
-- Name: actor_admin1_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.actor_admin1_boundary (
    actor_id uuid NOT NULL,
    admin1_boundary_id integer NOT NULL
);


--
-- Name: COLUMN actor_admin1_boundary.actor_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor_admin1_boundary.actor_id IS '(DC2Type:uuid)';


--
-- Name: actor_admin3_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.actor_admin3_boundary (
    actor_id uuid NOT NULL,
    admin3_boundary_id integer NOT NULL
);


--
-- Name: COLUMN actor_admin3_boundary.actor_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor_admin3_boundary.actor_id IS '(DC2Type:uuid)';


--
-- Name: actor_media_object; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.actor_media_object (
    actor_id uuid NOT NULL,
    media_object_id integer NOT NULL
);


--
-- Name: COLUMN actor_media_object.actor_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.actor_media_object.actor_id IS '(DC2Type:uuid)';


--
-- Name: admin1_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admin1_boundary (
    id integer NOT NULL,
    adm1_name character varying(255) NOT NULL,
    adm1_pcode character varying(255) NOT NULL,
    geometry public.geometry NOT NULL
);


--
-- Name: admin1_boundary_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.admin1_boundary_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: admin3_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admin3_boundary (
    id integer NOT NULL,
    adm3_name character varying(255) NOT NULL,
    adm3_pcode character varying(255) NOT NULL,
    adm2_name character varying(255) NOT NULL,
    adm2_pcode character varying(255) NOT NULL,
    adm1_name character varying(255) NOT NULL,
    adm1_pcode character varying(255) NOT NULL,
    geometry public.geometry NOT NULL
);


--
-- Name: admin3_boundary_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.admin3_boundary_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: app_content_comment; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.app_content_comment (
    id integer NOT NULL,
    created_by integer,
    updated_by integer,
    read_by_admin boolean NOT NULL,
    origin character varying(255) NOT NULL,
    message text NOT NULL,
    location public.geometry(Point) DEFAULT NULL::public.geometry,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    origin_url character varying(255) DEFAULT NULL::character varying
);


--
-- Name: app_content_comment_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.app_content_comment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: atlas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.atlas (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    atlas_group character varying(255) NOT NULL,
    "position" integer NOT NULL,
    logo_id integer,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


--
-- Name: atlas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.atlas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: atlas_qgis_map; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.atlas_qgis_map (
    atlas_id integer NOT NULL,
    qgis_map_id integer NOT NULL
);


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


--
-- Name: geo_data; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.geo_data (
    id integer NOT NULL,
    osm_id bigint,
    osm_type character varying(255),
    name character varying(255),
    latitude double precision,
    longitude double precision,
    bounds json,
    address json
);


--
-- Name: geo_data_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.geo_data_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: highlighted_item; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.highlighted_item (
    id integer NOT NULL,
    item_id character varying(255) NOT NULL,
    is_highlighted boolean NOT NULL,
    highlighted_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    "position" integer,
    item_type character varying(255) NOT NULL
);


--
-- Name: COLUMN highlighted_item.highlighted_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.highlighted_item.highlighted_at IS '(DC2Type:datetime_immutable)';


--
-- Name: highlighted_item_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.highlighted_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: media_object; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.media_object (
    id integer NOT NULL,
    file_path character varying(255) DEFAULT NULL::character varying,
    original_name character varying(255) DEFAULT NULL::character varying,
    mime_type character varying(255) DEFAULT NULL::character varying,
    dimensions json,
    size integer,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    type character varying(255) NOT NULL
);


--
-- Name: media_object_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.media_object_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: project; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project (
    id uuid NOT NULL,
    actor_id uuid,
    created_by integer,
    updated_by integer,
    geo_data_id integer,
    name character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    description text,
    focal_point_name character varying(255) NOT NULL,
    focal_point_position character varying(255) DEFAULT NULL::character varying,
    focal_point_email character varying(255) DEFAULT NULL::character varying,
    focal_point_tel character varying(255) DEFAULT NULL::character varying,
    focal_point_photo character varying(255) DEFAULT NULL::character varying,
    website character varying(255) DEFAULT NULL::character varying,
    deliverables text,
    calendar text,
    beneficiary_types json,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    slug character varying(128) DEFAULT NULL::character varying,
    is_validated boolean NOT NULL,
    creator_message text,
    logo_id integer,
    external_images text,
    other_beneficiary character varying(255) DEFAULT NULL::character varying,
    other_financing_type character varying(255) DEFAULT NULL::character varying,
    other_actor_in_charge character varying(255) DEFAULT NULL::character varying,
    other_thematic character varying(255) DEFAULT NULL::character varying,
    administrative_scopes text NOT NULL,
    other_actor character varying(255) DEFAULT NULL::character varying,
    financing_types character varying(255) DEFAULT 'autre'::character varying NOT NULL,
    thematics text,
    odds json,
    banoc character varying(128) DEFAULT NULL::character varying,
    banoc_url character varying(128) DEFAULT NULL::character varying
);


--
-- Name: COLUMN project.id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.id IS '(DC2Type:uuid)';


--
-- Name: COLUMN project.actor_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.actor_id IS '(DC2Type:uuid)';


--
-- Name: COLUMN project.external_images; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.external_images IS '(DC2Type:simple_array)';


--
-- Name: COLUMN project.administrative_scopes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.administrative_scopes IS '(DC2Type:simple_array)';


--
-- Name: COLUMN project.financing_types; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.financing_types IS '(DC2Type:simple_array)';


--
-- Name: COLUMN project.thematics; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project.thematics IS '(DC2Type:simple_array)';


--
-- Name: project_actor; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_actor (
    project_id uuid NOT NULL,
    actor_id uuid NOT NULL
);


--
-- Name: COLUMN project_actor.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project_actor.project_id IS '(DC2Type:uuid)';


--
-- Name: COLUMN project_actor.actor_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project_actor.actor_id IS '(DC2Type:uuid)';


--
-- Name: project_admin1_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_admin1_boundary (
    project_id uuid NOT NULL,
    admin1_boundary_id integer NOT NULL
);


--
-- Name: COLUMN project_admin1_boundary.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project_admin1_boundary.project_id IS '(DC2Type:uuid)';


--
-- Name: project_admin3_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_admin3_boundary (
    project_id uuid NOT NULL,
    admin3_boundary_id integer NOT NULL
);


--
-- Name: COLUMN project_admin3_boundary.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project_admin3_boundary.project_id IS '(DC2Type:uuid)';


--
-- Name: project_media_object; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.project_media_object (
    project_id uuid NOT NULL,
    media_object_id integer NOT NULL
);


--
-- Name: COLUMN project_media_object.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.project_media_object.project_id IS '(DC2Type:uuid)';


--
-- Name: projects_partners_media_object; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.projects_partners_media_object (
    project_id uuid NOT NULL,
    media_object_id integer NOT NULL
);


--
-- Name: COLUMN projects_partners_media_object.project_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.projects_partners_media_object.project_id IS '(DC2Type:uuid)';


--
-- Name: qgis_map; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.qgis_map (
    id integer NOT NULL,
    qgis_project_id integer,
    name character varying(255) NOT NULL,
    description text,
    needs_to_be_visualise_as_plain_image_instead_of_wms boolean NOT NULL,
    logo_id integer,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    qgis_map_type character varying(255) NOT NULL
);


--
-- Name: qgis_map_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.qgis_map_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: qgis_project; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.qgis_project (
    id integer NOT NULL,
    file_path character varying(255) DEFAULT NULL::character varying,
    layers json,
    name character varying(255) NOT NULL
);


--
-- Name: qgis_project_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.qgis_project_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: refresh_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.refresh_tokens (
    id integer NOT NULL,
    refresh_token character varying(128) NOT NULL,
    username character varying(255) NOT NULL,
    valid timestamp(0) without time zone NOT NULL
);


--
-- Name: refresh_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.refresh_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: refresh_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.refresh_tokens_id_seq OWNED BY public.refresh_tokens.id;


--
-- Name: resource; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.resource (
    id uuid NOT NULL,
    created_by integer,
    updated_by integer,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    type character varying(255) NOT NULL,
    link character varying(255) DEFAULT NULL::character varying,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    is_validated boolean NOT NULL,
    geo_data_id integer,
    format character varying(255) NOT NULL,
    start_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    file_id integer,
    author character varying(255) DEFAULT NULL::character varying,
    creator_message text,
    preview_image_id integer,
    other_type character varying(255) DEFAULT NULL::character varying,
    other_thematic character varying(255) DEFAULT NULL::character varying,
    thematics text,
    odds json,
    banoc character varying(128) DEFAULT NULL::character varying,
    banoc_url character varying(128) DEFAULT NULL::character varying,
    administrative_scopes text NOT NULL
);


--
-- Name: COLUMN resource.id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource.id IS '(DC2Type:uuid)';


--
-- Name: COLUMN resource.start_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource.start_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN resource.end_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource.end_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN resource.thematics; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource.thematics IS '(DC2Type:simple_array)';


--
-- Name: COLUMN resource.administrative_scopes; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource.administrative_scopes IS '(DC2Type:simple_array)';


--
-- Name: resource_admin1_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.resource_admin1_boundary (
    resource_id uuid NOT NULL,
    admin1_boundary_id integer NOT NULL
);


--
-- Name: COLUMN resource_admin1_boundary.resource_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource_admin1_boundary.resource_id IS '(DC2Type:uuid)';


--
-- Name: resource_admin3_boundary; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.resource_admin3_boundary (
    resource_id uuid NOT NULL,
    admin3_boundary_id integer NOT NULL
);


--
-- Name: COLUMN resource_admin3_boundary.resource_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.resource_admin3_boundary.resource_id IS '(DC2Type:uuid)';


--
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    logo_id integer,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) DEFAULT NULL::character varying,
    requested_roles json,
    organisation character varying(255) DEFAULT NULL::character varying,
    "position" character varying(255) DEFAULT NULL::character varying,
    phone character varying(20) DEFAULT NULL::character varying,
    sign_up_message text,
    description text,
    is_validated boolean NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL,
    has_seen_requested_roles boolean DEFAULT false NOT NULL
);


--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_like; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_like (
    id integer NOT NULL,
    user_id_id integer NOT NULL,
    content_id uuid NOT NULL
);


--
-- Name: COLUMN user_like.content_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_like.content_id IS '(DC2Type:uuid)';


--
-- Name: user_like_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_like_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_password_token; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_password_token (
    id integer NOT NULL,
    user_id integer NOT NULL,
    token character varying(50) NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: user_password_token_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_password_token_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: blocked_period blocked_period_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.blocked_period
    ADD CONSTRAINT blocked_period_pkey PRIMARY KEY (id);


--
-- Name: booking_media_object booking_media_object_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_media_object
    ADD CONSTRAINT booking_media_object_pkey PRIMARY KEY (booking_id, media_object_id);


--
-- Name: booking booking_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_pkey PRIMARY KEY (id);


--
-- Name: booking_resource booking_resource_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_resource
    ADD CONSTRAINT booking_resource_pkey PRIMARY KEY (booking_id, resource_id);


--
-- Name: event_activity_favorite event_activity_favorite_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_favorite
    ADD CONSTRAINT event_activity_favorite_pkey PRIMARY KEY (id);


--
-- Name: event_activity_type event_activity_type_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_type
    ADD CONSTRAINT event_activity_type_pkey PRIMARY KEY (id);


--
-- Name: information_source information_source_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.information_source
    ADD CONSTRAINT information_source_pkey PRIMARY KEY (id);


--
-- Name: notification notification_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.notification
    ADD CONSTRAINT notification_pkey PRIMARY KEY (id);


--
-- Name: space_admin space_admin_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_admin
    ADD CONSTRAINT space_admin_pkey PRIMARY KEY (user_id, space_id);


--
-- Name: space_media_object space_media_object_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_media_object
    ADD CONSTRAINT space_media_object_pkey PRIMARY KEY (space_id, media_object_id);


--
-- Name: space space_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT space_pkey PRIMARY KEY (id);


--
-- Name: status status_pkey; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.status
    ADD CONSTRAINT status_pkey PRIMARY KEY (id);


--
-- Name: status uniq_5353179377153098; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.status
    ADD CONSTRAINT uniq_5353179377153098 UNIQUE (code);


--
-- Name: event_activity_type uniq_59fe2b5fea750e8; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_type
    ADD CONSTRAINT uniq_59fe2b5fea750e8 UNIQUE (label);


--
-- Name: information_source uniq_62d8c30ea750e8; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.information_source
    ADD CONSTRAINT uniq_62d8c30ea750e8 UNIQUE (label);


--
-- Name: space uniq_78b8d00280e32c3e; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT uniq_78b8d00280e32c3e UNIQUE (geo_data_id);


--
-- Name: space uniq_78b8d002989d9b62; Type: CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT uniq_78b8d002989d9b62 UNIQUE (slug);


--
-- Name: actor_admin1_boundary actor_admin1_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin1_boundary
    ADD CONSTRAINT actor_admin1_boundary_pkey PRIMARY KEY (actor_id, admin1_boundary_id);


--
-- Name: actor_admin3_boundary actor_admin3_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin3_boundary
    ADD CONSTRAINT actor_admin3_boundary_pkey PRIMARY KEY (actor_id, admin3_boundary_id);


--
-- Name: actor_media_object actor_media_object_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_media_object
    ADD CONSTRAINT actor_media_object_pkey PRIMARY KEY (actor_id, media_object_id);


--
-- Name: actor actor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor
    ADD CONSTRAINT actor_pkey PRIMARY KEY (id);


--
-- Name: admin1_boundary admin1_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin1_boundary
    ADD CONSTRAINT admin1_boundary_pkey PRIMARY KEY (id);


--
-- Name: admin3_boundary admin3_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin3_boundary
    ADD CONSTRAINT admin3_boundary_pkey PRIMARY KEY (id);


--
-- Name: app_content_comment app_content_comment_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_content_comment
    ADD CONSTRAINT app_content_comment_pkey PRIMARY KEY (id);


--
-- Name: atlas atlas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.atlas
    ADD CONSTRAINT atlas_pkey PRIMARY KEY (id);


--
-- Name: atlas_qgis_map atlas_qgis_map_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.atlas_qgis_map
    ADD CONSTRAINT atlas_qgis_map_pkey PRIMARY KEY (atlas_id, qgis_map_id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: geo_data geo_data_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.geo_data
    ADD CONSTRAINT geo_data_pkey PRIMARY KEY (id);


--
-- Name: highlighted_item highlighted_item_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.highlighted_item
    ADD CONSTRAINT highlighted_item_pkey PRIMARY KEY (id);


--
-- Name: media_object media_object_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.media_object
    ADD CONSTRAINT media_object_pkey PRIMARY KEY (id);


--
-- Name: project_actor project_actor_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_actor
    ADD CONSTRAINT project_actor_pkey PRIMARY KEY (project_id, actor_id);


--
-- Name: project_admin1_boundary project_admin1_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin1_boundary
    ADD CONSTRAINT project_admin1_boundary_pkey PRIMARY KEY (project_id, admin1_boundary_id);


--
-- Name: project_admin3_boundary project_admin3_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin3_boundary
    ADD CONSTRAINT project_admin3_boundary_pkey PRIMARY KEY (project_id, admin3_boundary_id);


--
-- Name: project_media_object project_media_object_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_media_object
    ADD CONSTRAINT project_media_object_pkey PRIMARY KEY (project_id, media_object_id);


--
-- Name: project project_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: projects_partners_media_object projects_partners_media_object_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projects_partners_media_object
    ADD CONSTRAINT projects_partners_media_object_pkey PRIMARY KEY (project_id, media_object_id);


--
-- Name: qgis_map qgis_map_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qgis_map
    ADD CONSTRAINT qgis_map_pkey PRIMARY KEY (id);


--
-- Name: qgis_project qgis_project_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qgis_project
    ADD CONSTRAINT qgis_project_pkey PRIMARY KEY (id);


--
-- Name: refresh_tokens refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.refresh_tokens
    ADD CONSTRAINT refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: resource_admin1_boundary resource_admin1_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin1_boundary
    ADD CONSTRAINT resource_admin1_boundary_pkey PRIMARY KEY (resource_id, admin1_boundary_id);


--
-- Name: resource_admin3_boundary resource_admin3_boundary_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin3_boundary
    ADD CONSTRAINT resource_admin3_boundary_pkey PRIMARY KEY (resource_id, admin3_boundary_id);


--
-- Name: resource resource_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT resource_pkey PRIMARY KEY (id);


--
-- Name: user_like user_like_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_like
    ADD CONSTRAINT user_like_pkey PRIMARY KEY (id);


--
-- Name: user_password_token user_password_token_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_password_token
    ADD CONSTRAINT user_password_token_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_1847e25423575340; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_1847e25423575340 ON divercity.event_activity_favorite USING btree (space_id);


--
-- Name: idx_1847e25464de5a5; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_1847e25464de5a5 ON divercity.event_activity_favorite USING btree (media_object_id);


--
-- Name: idx_1847e254a76ed395; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_1847e254a76ed395 ON divercity.event_activity_favorite USING btree (user_id);


--
-- Name: idx_3c46d47c3301c60; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_3c46d47c3301c60 ON divercity.notification USING btree (booking_id);


--
-- Name: idx_3c46d47ca76ed395; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_3c46d47ca76ed395 ON divercity.notification USING btree (user_id);


--
-- Name: idx_442f602523575340; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_442f602523575340 ON divercity.space_admin USING btree (space_id);


--
-- Name: idx_442f6025a76ed395; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_442f6025a76ed395 ON divercity.space_admin USING btree (user_id);


--
-- Name: idx_6905509a89329d25; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_6905509a89329d25 ON divercity.booking_resource USING btree (resource_id);


--
-- Name: idx_72b9899664de5a5; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_72b9899664de5a5 ON divercity.space_media_object USING btree (media_object_id);


--
-- Name: idx_78b8d00216fe72e1; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_78b8d00216fe72e1 ON divercity.space USING btree (updated_by);


--
-- Name: idx_78b8d002de12ab56; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_78b8d002de12ab56 ON divercity.space USING btree (created_by);


--
-- Name: idx_8ebea44864de5a5; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_8ebea44864de5a5 ON divercity.booking_media_object USING btree (media_object_id);


--
-- Name: idx_9d23201d23575340; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201d23575340 ON divercity.booking USING btree (space_id);


--
-- Name: idx_9d23201d2bb019e3; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201d2bb019e3 ON divercity.booking USING btree (information_source_id);


--
-- Name: idx_9d23201d6bf700bd; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201d6bf700bd ON divercity.booking USING btree (status_id);


--
-- Name: idx_9d23201da76ed395; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201da76ed395 ON divercity.booking USING btree (user_id);


--
-- Name: idx_9d23201dcc36ff0e; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201dcc36ff0e ON divercity.booking USING btree (processing_user_id);


--
-- Name: idx_9d23201de30e19bb; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_9d23201de30e19bb ON divercity.booking USING btree (event_activity_type_id);


--
-- Name: idx_fb43a95023575340; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_fb43a95023575340 ON divercity.blocked_period USING btree (space_id);


--
-- Name: idx_fb43a950de12ab56; Type: INDEX; Schema: divercity; Owner: -
--

CREATE INDEX idx_fb43a950de12ab56 ON divercity.blocked_period USING btree (created_by);


--
-- Name: idx_11a6ae5616fe72e1; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_11a6ae5616fe72e1 ON public.app_content_comment USING btree (updated_by);


--
-- Name: idx_11a6ae56de12ab56; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_11a6ae56de12ab56 ON public.app_content_comment USING btree (created_by);


--
-- Name: idx_18e6a66710daf24a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_18e6a66710daf24a ON public.actor_media_object USING btree (actor_id);


--
-- Name: idx_18e6a66764de5a5; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_18e6a66764de5a5 ON public.actor_media_object USING btree (media_object_id);


--
-- Name: idx_25c294dc4577faf0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_25c294dc4577faf0 ON public.qgis_map USING btree (qgis_project_id);


--
-- Name: idx_27a78b7c166d1f9c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_27a78b7c166d1f9c ON public.projects_partners_media_object USING btree (project_id);


--
-- Name: idx_27a78b7c64de5a5; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_27a78b7c64de5a5 ON public.projects_partners_media_object USING btree (media_object_id);


--
-- Name: idx_2fb3d0ee10daf24a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_2fb3d0ee10daf24a ON public.project USING btree (actor_id);


--
-- Name: idx_2fb3d0ee16fe72e1; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_2fb3d0ee16fe72e1 ON public.project USING btree (updated_by);


--
-- Name: idx_2fb3d0eede12ab56; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_2fb3d0eede12ab56 ON public.project USING btree (created_by);


--
-- Name: idx_2fb3d0eef98f144a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_2fb3d0eef98f144a ON public.project USING btree (logo_id);


--
-- Name: idx_3e73d551166d1f9c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_3e73d551166d1f9c ON public.project_media_object USING btree (project_id);


--
-- Name: idx_3e73d55164de5a5; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_3e73d55164de5a5 ON public.project_media_object USING btree (media_object_id);


--
-- Name: idx_447556f916fe72e1; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_447556f916fe72e1 ON public.actor USING btree (updated_by);


--
-- Name: idx_447556f9de12ab56; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_447556f9de12ab56 ON public.actor USING btree (created_by);


--
-- Name: idx_57205f6710daf24a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_57205f6710daf24a ON public.project_actor USING btree (actor_id);


--
-- Name: idx_57205f67166d1f9c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_57205f67166d1f9c ON public.project_actor USING btree (project_id);


--
-- Name: idx_79caca1a5f1bc794; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_79caca1a5f1bc794 ON public.resource_admin1_boundary USING btree (admin1_boundary_id);


--
-- Name: idx_79caca1a89329d25; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_79caca1a89329d25 ON public.resource_admin1_boundary USING btree (resource_id);


--
-- Name: idx_7d3f1a273f4772df; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_7d3f1a273f4772df ON public.resource_admin3_boundary USING btree (admin3_boundary_id);


--
-- Name: idx_7d3f1a2789329d25; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_7d3f1a2789329d25 ON public.resource_admin3_boundary USING btree (resource_id);


--
-- Name: idx_aa3d30e166d1f9c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_aa3d30e166d1f9c ON public.project_admin3_boundary USING btree (project_id);


--
-- Name: idx_aa3d30e3f4772df; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_aa3d30e3f4772df ON public.project_admin3_boundary USING btree (admin3_boundary_id);


--
-- Name: idx_aac7d7c910daf24a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_aac7d7c910daf24a ON public.actor_admin1_boundary USING btree (actor_id);


--
-- Name: idx_aac7d7c95f1bc794; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_aac7d7c95f1bc794 ON public.actor_admin1_boundary USING btree (admin1_boundary_id);


--
-- Name: idx_ae3207f410daf24a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ae3207f410daf24a ON public.actor_admin3_boundary USING btree (actor_id);


--
-- Name: idx_ae3207f43f4772df; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ae3207f43f4772df ON public.actor_admin3_boundary USING btree (admin3_boundary_id);


--
-- Name: idx_bc91f41616fe72e1; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_bc91f41616fe72e1 ON public.resource USING btree (updated_by);


--
-- Name: idx_bc91f41693cb796c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_bc91f41693cb796c ON public.resource USING btree (file_id);


--
-- Name: idx_bc91f416de12ab56; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_bc91f416de12ab56 ON public.resource USING btree (created_by);


--
-- Name: idx_d52eb37da76ed395; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_d52eb37da76ed395 ON public.user_password_token USING btree (user_id);


--
-- Name: idx_d6e20c7a9d86650f; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_d6e20c7a9d86650f ON public.user_like USING btree (user_id_id);


--
-- Name: idx_e560333166d1f9c; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_e560333166d1f9c ON public.project_admin1_boundary USING btree (project_id);


--
-- Name: idx_e5603335f1bc794; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_e5603335f1bc794 ON public.project_admin1_boundary USING btree (admin1_boundary_id);


--
-- Name: idx_f65392725aaa09f8; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f65392725aaa09f8 ON public.atlas_qgis_map USING btree (atlas_id);


--
-- Name: idx_f65392728295e5c3; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f65392728295e5c3 ON public.atlas_qgis_map USING btree (qgis_map_id);


--
-- Name: idx_project_slug_is_validated; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_project_slug_is_validated ON public.project USING btree (slug, is_validated);


--
-- Name: uniq_1be98136126f525e; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_1be98136126f525e ON public.highlighted_item USING btree (item_id);


--
-- Name: uniq_25c294dcf98f144a; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_25c294dcf98f144a ON public.qgis_map USING btree (logo_id);


--
-- Name: uniq_2fb3d0ee80e32c3e; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_2fb3d0ee80e32c3e ON public.project USING btree (geo_data_id);


--
-- Name: uniq_2fb3d0ee989d9b62; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_2fb3d0ee989d9b62 ON public.project USING btree (slug);


--
-- Name: uniq_2fb3d0eed2dde2f6; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_2fb3d0eed2dde2f6 ON public.project USING btree (banoc);


--
-- Name: uniq_2fb3d0eeec3d194b; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_2fb3d0eeec3d194b ON public.project USING btree (banoc_url);


--
-- Name: uniq_447556f980e32c3e; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_447556f980e32c3e ON public.actor USING btree (geo_data_id);


--
-- Name: uniq_447556f9989d9b62; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_447556f9989d9b62 ON public.actor USING btree (slug);


--
-- Name: uniq_447556f9d2dde2f6; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_447556f9d2dde2f6 ON public.actor USING btree (banoc);


--
-- Name: uniq_447556f9ec3d194b; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_447556f9ec3d194b ON public.actor USING btree (banoc_url);


--
-- Name: uniq_447556f9f98f144a; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_447556f9f98f144a ON public.actor USING btree (logo_id);


--
-- Name: uniq_720ad60ff98f144a; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_720ad60ff98f144a ON public.atlas USING btree (logo_id);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON public."user" USING btree (email);


--
-- Name: uniq_8d93d649f98f144a; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_8d93d649f98f144a ON public."user" USING btree (logo_id);


--
-- Name: uniq_9bace7e1c74f2195; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_9bace7e1c74f2195 ON public.refresh_tokens USING btree (refresh_token);


--
-- Name: uniq_bc91f41680e32c3e; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_bc91f41680e32c3e ON public.resource USING btree (geo_data_id);


--
-- Name: uniq_bc91f416d2dde2f6; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_bc91f416d2dde2f6 ON public.resource USING btree (banoc);


--
-- Name: uniq_bc91f416ec3d194b; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_bc91f416ec3d194b ON public.resource USING btree (banoc_url);


--
-- Name: uniq_bc91f416fae957cd; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_bc91f416fae957cd ON public.resource USING btree (preview_image_id);


--
-- Name: uniq_d52eb37d5f37a13b; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_d52eb37d5f37a13b ON public.user_password_token USING btree (token);


--
-- Name: unique_like_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX unique_like_idx ON public.user_like USING btree (user_id_id, content_id);


--
-- Name: blocked_period blocked_period_created_by_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.blocked_period
    ADD CONSTRAINT blocked_period_created_by_fkey FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: blocked_period blocked_period_space_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.blocked_period
    ADD CONSTRAINT blocked_period_space_id_fkey FOREIGN KEY (space_id) REFERENCES divercity.space(id) ON DELETE CASCADE;


--
-- Name: booking booking_event_activity_type_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_event_activity_type_id_fkey FOREIGN KEY (event_activity_type_id) REFERENCES divercity.event_activity_type(id);


--
-- Name: booking booking_information_source_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_information_source_id_fkey FOREIGN KEY (information_source_id) REFERENCES divercity.information_source(id);


--
-- Name: booking booking_processing_user_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_processing_user_id_fkey FOREIGN KEY (processing_user_id) REFERENCES public."user"(id);


--
-- Name: booking booking_space_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_space_id_fkey FOREIGN KEY (space_id) REFERENCES divercity.space(id);


--
-- Name: booking booking_status_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_status_id_fkey FOREIGN KEY (status_id) REFERENCES divercity.status(id);


--
-- Name: booking booking_user_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking
    ADD CONSTRAINT booking_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: event_activity_favorite event_activity_favorite_media_object_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_favorite
    ADD CONSTRAINT event_activity_favorite_media_object_id_fkey FOREIGN KEY (media_object_id) REFERENCES public.media_object(id);


--
-- Name: event_activity_favorite event_activity_favorite_space_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_favorite
    ADD CONSTRAINT event_activity_favorite_space_id_fkey FOREIGN KEY (space_id) REFERENCES divercity.space(id) ON DELETE CASCADE;


--
-- Name: event_activity_favorite event_activity_favorite_user_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.event_activity_favorite
    ADD CONSTRAINT event_activity_favorite_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE CASCADE;


--
-- Name: booking_resource fk_6905509a3301c60; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_resource
    ADD CONSTRAINT fk_6905509a3301c60 FOREIGN KEY (booking_id) REFERENCES divercity.booking(id);


--
-- Name: booking_resource fk_6905509a89329d25; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_resource
    ADD CONSTRAINT fk_6905509a89329d25 FOREIGN KEY (resource_id) REFERENCES public.resource(id);


--
-- Name: space_media_object fk_72b9899623575340; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_media_object
    ADD CONSTRAINT fk_72b9899623575340 FOREIGN KEY (space_id) REFERENCES divercity.space(id);


--
-- Name: space_media_object fk_72b9899664de5a5; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_media_object
    ADD CONSTRAINT fk_72b9899664de5a5 FOREIGN KEY (media_object_id) REFERENCES public.media_object(id);


--
-- Name: booking_media_object fk_8ebea4483301c60; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_media_object
    ADD CONSTRAINT fk_8ebea4483301c60 FOREIGN KEY (booking_id) REFERENCES divercity.booking(id);


--
-- Name: booking_media_object fk_8ebea44864de5a5; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.booking_media_object
    ADD CONSTRAINT fk_8ebea44864de5a5 FOREIGN KEY (media_object_id) REFERENCES public.media_object(id);


--
-- Name: notification notification_booking_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.notification
    ADD CONSTRAINT notification_booking_id_fkey FOREIGN KEY (booking_id) REFERENCES divercity.booking(id) ON DELETE CASCADE;


--
-- Name: notification notification_user_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.notification
    ADD CONSTRAINT notification_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: space_admin space_admin_space_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_admin
    ADD CONSTRAINT space_admin_space_id_fkey FOREIGN KEY (space_id) REFERENCES divercity.space(id) ON DELETE CASCADE;


--
-- Name: space_admin space_admin_user_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space_admin
    ADD CONSTRAINT space_admin_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE CASCADE;


--
-- Name: space space_created_by_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT space_created_by_fkey FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: space space_geo_data_id_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT space_geo_data_id_fkey FOREIGN KEY (geo_data_id) REFERENCES public.geo_data(id);


--
-- Name: space space_updated_by_fkey; Type: FK CONSTRAINT; Schema: divercity; Owner: -
--

ALTER TABLE ONLY divercity.space
    ADD CONSTRAINT space_updated_by_fkey FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: app_content_comment fk_11a6ae5616fe72e1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_content_comment
    ADD CONSTRAINT fk_11a6ae5616fe72e1 FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: app_content_comment fk_11a6ae56de12ab56; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.app_content_comment
    ADD CONSTRAINT fk_11a6ae56de12ab56 FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: actor_media_object fk_18e6a66710daf24a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_media_object
    ADD CONSTRAINT fk_18e6a66710daf24a FOREIGN KEY (actor_id) REFERENCES public.actor(id) ON DELETE CASCADE;


--
-- Name: actor_media_object fk_18e6a66764de5a5; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_media_object
    ADD CONSTRAINT fk_18e6a66764de5a5 FOREIGN KEY (media_object_id) REFERENCES public.media_object(id) ON DELETE CASCADE;


--
-- Name: qgis_map fk_25c294dc4577faf0; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qgis_map
    ADD CONSTRAINT fk_25c294dc4577faf0 FOREIGN KEY (qgis_project_id) REFERENCES public.qgis_project(id);


--
-- Name: qgis_map fk_25c294dcf98f144a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.qgis_map
    ADD CONSTRAINT fk_25c294dcf98f144a FOREIGN KEY (logo_id) REFERENCES public.media_object(id);


--
-- Name: projects_partners_media_object fk_27a78b7c166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projects_partners_media_object
    ADD CONSTRAINT fk_27a78b7c166d1f9c FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: projects_partners_media_object fk_27a78b7c64de5a5; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projects_partners_media_object
    ADD CONSTRAINT fk_27a78b7c64de5a5 FOREIGN KEY (media_object_id) REFERENCES public.media_object(id) ON DELETE CASCADE;


--
-- Name: project fk_2fb3d0ee10daf24a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT fk_2fb3d0ee10daf24a FOREIGN KEY (actor_id) REFERENCES public.actor(id);


--
-- Name: project fk_2fb3d0ee16fe72e1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT fk_2fb3d0ee16fe72e1 FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: project fk_2fb3d0ee80e32c3e; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT fk_2fb3d0ee80e32c3e FOREIGN KEY (geo_data_id) REFERENCES public.geo_data(id);


--
-- Name: project fk_2fb3d0eede12ab56; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT fk_2fb3d0eede12ab56 FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: project fk_2fb3d0eef98f144a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project
    ADD CONSTRAINT fk_2fb3d0eef98f144a FOREIGN KEY (logo_id) REFERENCES public.media_object(id);


--
-- Name: project_media_object fk_3e73d551166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_media_object
    ADD CONSTRAINT fk_3e73d551166d1f9c FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: project_media_object fk_3e73d55164de5a5; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_media_object
    ADD CONSTRAINT fk_3e73d55164de5a5 FOREIGN KEY (media_object_id) REFERENCES public.media_object(id) ON DELETE CASCADE;


--
-- Name: actor fk_447556f916fe72e1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor
    ADD CONSTRAINT fk_447556f916fe72e1 FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: actor fk_447556f980e32c3e; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor
    ADD CONSTRAINT fk_447556f980e32c3e FOREIGN KEY (geo_data_id) REFERENCES public.geo_data(id);


--
-- Name: actor fk_447556f9de12ab56; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor
    ADD CONSTRAINT fk_447556f9de12ab56 FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: actor fk_447556f9f98f144a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor
    ADD CONSTRAINT fk_447556f9f98f144a FOREIGN KEY (logo_id) REFERENCES public.media_object(id);


--
-- Name: project_actor fk_57205f6710daf24a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_actor
    ADD CONSTRAINT fk_57205f6710daf24a FOREIGN KEY (actor_id) REFERENCES public.actor(id) ON DELETE CASCADE;


--
-- Name: project_actor fk_57205f67166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_actor
    ADD CONSTRAINT fk_57205f67166d1f9c FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: atlas fk_720ad60ff98f144a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.atlas
    ADD CONSTRAINT fk_720ad60ff98f144a FOREIGN KEY (logo_id) REFERENCES public.media_object(id);


--
-- Name: resource_admin1_boundary fk_79caca1a5f1bc794; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin1_boundary
    ADD CONSTRAINT fk_79caca1a5f1bc794 FOREIGN KEY (admin1_boundary_id) REFERENCES public.admin1_boundary(id) ON DELETE CASCADE;


--
-- Name: resource_admin1_boundary fk_79caca1a89329d25; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin1_boundary
    ADD CONSTRAINT fk_79caca1a89329d25 FOREIGN KEY (resource_id) REFERENCES public.resource(id) ON DELETE CASCADE;


--
-- Name: resource_admin3_boundary fk_7d3f1a273f4772df; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin3_boundary
    ADD CONSTRAINT fk_7d3f1a273f4772df FOREIGN KEY (admin3_boundary_id) REFERENCES public.admin3_boundary(id) ON DELETE CASCADE;


--
-- Name: resource_admin3_boundary fk_7d3f1a2789329d25; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource_admin3_boundary
    ADD CONSTRAINT fk_7d3f1a2789329d25 FOREIGN KEY (resource_id) REFERENCES public.resource(id) ON DELETE CASCADE;


--
-- Name: user fk_8d93d649f98f144a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT fk_8d93d649f98f144a FOREIGN KEY (logo_id) REFERENCES public.media_object(id);


--
-- Name: project_admin3_boundary fk_aa3d30e166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin3_boundary
    ADD CONSTRAINT fk_aa3d30e166d1f9c FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: project_admin3_boundary fk_aa3d30e3f4772df; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin3_boundary
    ADD CONSTRAINT fk_aa3d30e3f4772df FOREIGN KEY (admin3_boundary_id) REFERENCES public.admin3_boundary(id) ON DELETE CASCADE;


--
-- Name: actor_admin1_boundary fk_aac7d7c910daf24a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin1_boundary
    ADD CONSTRAINT fk_aac7d7c910daf24a FOREIGN KEY (actor_id) REFERENCES public.actor(id) ON DELETE CASCADE;


--
-- Name: actor_admin1_boundary fk_aac7d7c95f1bc794; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin1_boundary
    ADD CONSTRAINT fk_aac7d7c95f1bc794 FOREIGN KEY (admin1_boundary_id) REFERENCES public.admin1_boundary(id) ON DELETE CASCADE;


--
-- Name: actor_admin3_boundary fk_ae3207f410daf24a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin3_boundary
    ADD CONSTRAINT fk_ae3207f410daf24a FOREIGN KEY (actor_id) REFERENCES public.actor(id) ON DELETE CASCADE;


--
-- Name: actor_admin3_boundary fk_ae3207f43f4772df; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.actor_admin3_boundary
    ADD CONSTRAINT fk_ae3207f43f4772df FOREIGN KEY (admin3_boundary_id) REFERENCES public.admin3_boundary(id) ON DELETE CASCADE;


--
-- Name: resource fk_bc91f41616fe72e1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f41616fe72e1 FOREIGN KEY (updated_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: resource fk_bc91f41680e32c3e; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f41680e32c3e FOREIGN KEY (geo_data_id) REFERENCES public.geo_data(id);


--
-- Name: resource fk_bc91f41693cb796c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f41693cb796c FOREIGN KEY (file_id) REFERENCES public.media_object(id);


--
-- Name: resource fk_bc91f416de12ab56; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f416de12ab56 FOREIGN KEY (created_by) REFERENCES public."user"(id) ON DELETE SET NULL;


--
-- Name: resource fk_bc91f416fae957cd; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.resource
    ADD CONSTRAINT fk_bc91f416fae957cd FOREIGN KEY (preview_image_id) REFERENCES public.media_object(id);


--
-- Name: user_password_token fk_d52eb37da76ed395; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_password_token
    ADD CONSTRAINT fk_d52eb37da76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: user_like fk_d6e20c7a9d86650f; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_like
    ADD CONSTRAINT fk_d6e20c7a9d86650f FOREIGN KEY (user_id_id) REFERENCES public."user"(id);


--
-- Name: project_admin1_boundary fk_e560333166d1f9c; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin1_boundary
    ADD CONSTRAINT fk_e560333166d1f9c FOREIGN KEY (project_id) REFERENCES public.project(id) ON DELETE CASCADE;


--
-- Name: project_admin1_boundary fk_e5603335f1bc794; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.project_admin1_boundary
    ADD CONSTRAINT fk_e5603335f1bc794 FOREIGN KEY (admin1_boundary_id) REFERENCES public.admin1_boundary(id) ON DELETE CASCADE;


--
-- Name: atlas_qgis_map fk_f65392725aaa09f8; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.atlas_qgis_map
    ADD CONSTRAINT fk_f65392725aaa09f8 FOREIGN KEY (atlas_id) REFERENCES public.atlas(id) ON DELETE CASCADE;


--
-- Name: atlas_qgis_map fk_f65392728295e5c3; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.atlas_qgis_map
    ADD CONSTRAINT fk_f65392728295e5c3 FOREIGN KEY (qgis_map_id) REFERENCES public.qgis_map(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict nIcO9yWgD4L849rakT5t5XPzWgXJha2g8tCv3hHbpXxGZ6BM4G4CtTXHNLZZrOX

